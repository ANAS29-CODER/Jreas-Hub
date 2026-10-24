<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\CategoryType;
use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('type', CategoryType::Course->value)->get();

        $selectedCategory = $request->integer('category') ?: null;

        $courses = Course::query()
            ->where('status', PublishStatus::Published->value)
            ->when($selectedCategory, fn ($query) => $query->where('category_id', $selectedCategory))
            ->latest('start_date')
            ->get();

        return view('frontend.courses.index', compact('categories', 'courses', 'selectedCategory'));
    }

    public function show(Course $course)
    {
        abort_unless($course->status === PublishStatus::Published, 404);

        return view('frontend.courses.show', compact('course'));
    }
}
