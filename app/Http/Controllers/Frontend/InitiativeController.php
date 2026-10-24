<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\InitiativeType;
use App\Enums\PublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Initiative;
use Illuminate\Http\Request;

class InitiativeController extends Controller
{
    public function index(Request $request)
    {
        $selectedType = $request->get('type');

        $initiatives = Initiative::query()
            ->where('status', PublishStatus::Published->value)
            ->when($selectedType, fn ($query) => $query->where('type', $selectedType))
            ->orderBy('start_date')
            ->get();

        $types = InitiativeType::cases();

        return view('frontend.initiatives.index', compact('initiatives', 'types', 'selectedType'));
    }

    public function show(Initiative $initiative)
    {
        abort_unless($initiative->status === PublishStatus::Published, 404);

        return view('frontend.initiatives.show', compact('initiative'));
    }
}
