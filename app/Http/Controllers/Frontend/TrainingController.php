<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Training;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('type', CategoryType::Training->value)->get();

        $selectedCategory = $request->integer('category') ?: null;

        $trainings = Training::query()
            ->where('status', PublishStatus::Published->value)
            ->when($selectedCategory, fn ($query) => $query->where('category_id', $selectedCategory))
            ->orderBy('start_date')
            ->get();

        return view('frontend.trainings.index', compact('categories', 'trainings', 'selectedCategory'));
    }

    public function show(Training $training)
    {
        abort_unless($training->status === PublishStatus::Published, 404);

        return view('frontend.trainings.show', compact('training'));
    }
}
