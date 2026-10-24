<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Workshop;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('type', CategoryType::Workshop->value)->get();

        $selectedCategory = $request->integer('category') ?: null;

        $workshops = Workshop::query()
            ->where('status', PublishStatus::Published->value)
            ->when($selectedCategory, fn ($query) => $query->where('category_id', $selectedCategory))
            ->orderBy('date')
            ->get();

        return view('frontend.workshops.index', compact('categories', 'workshops', 'selectedCategory'));
    }

    public function show(Workshop $workshop)
    {
        abort_unless($workshop->status === PublishStatus::Published, 404);

        return view('frontend.workshops.show', compact('workshop'));
    }
}
