<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SiteGallery;

class HomeController extends Controller
{
    public function index()
    {
        $galleryImages = SiteGallery::orderBy('sort_order')->get();
        $activities = ActivityLog::latest('date')->take(4)->get();

        return view('frontend.home', compact('galleryImages', 'activities'));
    }
}
