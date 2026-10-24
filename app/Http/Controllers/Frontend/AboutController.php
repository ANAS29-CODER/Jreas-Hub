<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PageSlug;
use App\Http\Controllers\Controller;
use App\Models\Page;

class AboutController extends Controller
{
    public function index()
    {
        $aboutPage = Page::where('slug', PageSlug::About->value)->first();

        return view('frontend.about', compact('aboutPage'));
    }
}
