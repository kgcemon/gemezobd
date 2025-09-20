<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CronJobController extends Controller
{
    public function freeFireAutoTopUpJob()
    {
        $orders = Order::where('status', 'processing')->whereNull('order_note')->limit(4)->get();

        try {
            foreach ($orders as $order) {
                DB::beginTransaction();

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
                        ])->post('http://15.235.147.4/topup', [
                            "playerId"   => $order->customer_data,
                            "denom"      => $d,
                            "type"       => $type,
                            "voucherCode"=> $code->code,
                            "webhook"    => "https://admin.GamezoBD.com/api/auto-webhooks"
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
    }
}
