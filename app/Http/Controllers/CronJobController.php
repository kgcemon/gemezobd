<?php

namespace App\Http\Controllers;

use App\Mail\SendPinsMail;
use App\Models\Code;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CronJobController extends Controller
{
    public function freeFireAutoTopUpJob()
    {


        try {

            $orders = Order::where('status', 'processing')->whereNull('order_note')->limit(4)->get();

            try {
                foreach ($orders as $order) {
                    DB::beginTransaction();

                    if ($order->item->denom === "2000") {
                        $success = $this->sendGiftCard($order);
                        if ($success) {
                            DB::commit();
                        } else {
                            DB::rollBack();
                        }

                        continue;
                    }

                    $order = Order::lockForUpdate()->find($order->id);

                    if ($order->status !== 'processing' || $order->order_note !== null) {
                        DB::rollBack();
                        continue;
                    }

                    $denom = (string) $order->item->denom ?? '';

                    if ($denom == null) {
                        DB::rollBack();
                        continue;
                    }

                    $denoms = explode(',', $denom);


                    // Count input requirements (কতবার কোন denom দরকার)
                    $counts = array_count_values($denoms);

                    $missing = [];

                    foreach ($counts as $value => $needed) {
                        $available = Code::where('denom', $value)->where('status', 'unused')
                            ->count();

                        if ($available < $needed) {
                            $missing[$value] = [
                                'needed'    => $needed,
                                'available' => $available
                            ];
                        }
                    }


                    if ($missing) {
                        DB::rollBack();
                        continue;
                    }

                    foreach ($denoms as $d) {

                        $code = Code::where('denom', $d)->where('status', 'unused')
                            ->lockForUpdate()
                            ->first();

                        if (!$code) {
                            DB::rollBack();
                            continue;
                        }
                        $type = (Str::startsWith($code->code, 'UPBD')) ? 2 : ((Str::startsWith($code->code, 'BDMB')) ? 1 : 1);

                        try {
                            $response = Http::withHeaders([
                                'Content-Type' => 'application/json',
                                'Accept' => 'application/json',
                                'RA-SECRET-KEY' => 'kpDvM4m9AOTl0+4Gcnvm7a+VgLJFjSNvuDVC9Jl6wH/RxXJqqCb0RQ==',
                            ])->post('https://webhook.site/2787b80c-0e00-4f14-b6cf-d8fbf1eaab3f', [
                                "playerid" => trim($order->customer_data),
                                "pacakge" => $d,
                                "code" => $code->code,
                                "orderid" => $order->id,
                                "url" => "https://webhook.site/2787b80c-0e00-4f14-b6cf-d8fbf1eaab3f",
                                "shell_balance" => 28,
                                "ourstock" => "1"
                            ]);

                        }catch (\Exception $exception){$order->order_note = 'server error';}

                        $data = $response->json();
                        $uid = $data['uid'] ?? null;
                        $order->status = 'Delivery Running';
                        $order->order_note = $uid ?? null;
                        $order->save();
                        $code->status = 'used';
                        $code->uid = $uid ?? null;
                        $code->order_id = $order->id;
                        if (empty($uid)){
                            $code->active = false;
                        }
                        $code->save();
                    }

                    DB::commit();
                }

                return 'Cron job run successfully';
            } catch (\Exception $exception) {
                DB::rollBack();
                return $exception->getMessage();
            }
        }    finally {

        }
    }

    private function sendGiftCard($order): bool
    {
        // Lock the order row
        $order = Order::lockForUpdate()->find($order->id);

        if (!$order || $order->status === 'delivered') {
            return false; // already processed
        }

        DB::beginTransaction();
        try {
            $email = $order->email;
            $total = Code::where('item_id', $order->item_id)
                ->where('status', 'unused')
                ->lockForUpdate()
                ->count();

            if ($total < $order->quantity || !$email) {
                DB::rollBack();
                return false;
            }

            $codes = Code::where('item_id', $order->item_id)
                ->where('status', 'unused')
                ->lockForUpdate()
                ->limit($order->quantity)
                ->get();

            if ($codes->isEmpty()) {
                DB::rollBack();
                return false;
            }

            $pins = $codes->map(function ($code) use ($order) {
                return [
                    'pin'    => $code->code,
                    'name'   => $order->item->name,
                ];
            })->toArray();

            // Update codes
            Code::whereIn('id', $codes->pluck('id'))->update([
                'status'   => 'used',
                'order_id' => $order->id,
            ]);

            // Update order
            $order->status = 'delivered';
            $order->save();

            DB::commit();

            // Mail send after commit
            try {
                Mail::to($email)->send(new SendPinsMail($order->name ?? 'Customer', $pins));
            } catch (\Exception $exception) {
                \Log::error("SendPinsMail failed for order {$order->id}: {$exception->getMessage()}");
            }

            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }


}
