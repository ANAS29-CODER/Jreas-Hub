<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PageSlug;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contactPage = Page::where('slug', PageSlug::Contact->value)->first();

        return view('frontend.contact', compact('contactPage'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create($validated);

        return back()->withFragment('contact')->with('status', 'Your message has been sent. We will get back to you soon.');
    }
}
