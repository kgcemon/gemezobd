<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\HelpLine;
use App\Models\SliderImages;
use App\Models\UpdateNotice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class HomePageController extends Controller
{
    public function index()
    {
        $sliderImages = Cache::remember('slider_images', 86400, function () {
            return SliderImages::all();
        });

        $notice = Cache::remember('notice_update', 86400, function () {
            return DB::table('notice_update')->first();
        });

        return response()->json([
            'status' => true,
            'sliderImages' => $sliderImages,
            'update' => $notice,
        ]);
    }
    public function notice()
    {
        $notice = UpdateNotice::all();
        return response()->json([
            'status' => true,
            'data' => $notice,
        ]);
    }

    public function helpLine()
    {
        $helpLine = HelpLine::all();
        return response()->json([
            'status' => true,
            'data' => $helpLine,
        ]);
    }
}
