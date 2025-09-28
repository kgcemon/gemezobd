<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function privacyPolicy()
    {
        return view('privacy-policy');
    }
}
