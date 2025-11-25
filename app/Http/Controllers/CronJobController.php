<?php

namespace App\Http\Controllers;

use App\Models\Api;
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
            $denomsForShell = ["108593", "108592", "108591", "108590", "108589", "108588", "LITE", "3D", "7D", "30D"];

            try {
                foreach ($orders as $order) {
                    DB::beginTransaction();

                    if (in_array($order->item->denom, $denomsForShell)) {
                        $success = $this->shellsTopUp($order);
                        if ($success) {
                            DB::commit();
                        } else {
                            DB::rollBack();
                        }
                        continue;
                    }

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
//                                'Accept' => 'application/json',
//                                'RA-SECRET-KEY' => 'kpDvM4m9AOTl0+4Gcnvm7a+VgLJFjSNvuDVC9Jl6wH/RxXJqqCb0RQ==',
                            ])->post('http://15.235.147.112:3333/complete', [
                                "playerid" => trim($order->customer_data),
                                "pacakge" => "$d",
                                "code" => "$code->code",
                                "orderid" => $order->id,
                                "url" => "https://gamezobd.com/api/auto-webhooks",
                                "shell_balance" => 28,
                                "ourstock" => "1",
                            ]);

                        }catch (\Exception $exception){$order->order_note = 'server error';}

                        $data = $response->json();
                        $uid = $data['uid'] ?? $order->id;
                        $order->status = 'Delivery Running';
                        $order->order_note = $uid ?? $order->id;
                        $order->save();
                        $code->status = 'used';
//                        $code->uid = $uid ?? null;
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

            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    public function shellsTopUp($order): bool
    {
        $denom = (string) $order->item->denom ?? '';

        $url =  'http://15.235.147.112:3333/complete';
        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ],)->post($url,[
                "playerid" => "$order->customer_data",
                "pacakge" => "$denom",
                "code" => "shell",
                "orderid" => $order->id,
                "url" => "https://gamezobd.com/api/auto-webhooks",
                "username" => "bondff07@gmail.com",
                "password" => "@@B0nD007@1",
                "autocode" => "OQOCQOAYFR6AIW6C",
                "tgbotid" => "701657976",
                "shell_balance" => 28,
                "ourstock" => 1
            ]);
        }catch (\Exception $exception){
            return false;
        }
        if ($response->successful()) {
            $order->order_note = $order->id;
            $order->status = 'Delivery Running';
            $order->save();
            return true;
        }
        return false;
    }


}
