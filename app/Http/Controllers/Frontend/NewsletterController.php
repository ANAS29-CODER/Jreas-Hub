<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'newsletter_email' => ['required', 'email', 'max:255'],
        ]);

        // Subscribing twice is not an error; the visitor sees the same confirmation.
        NewsletterSubscriber::firstOrCreate(['email' => strtolower($validated['newsletter_email'])]);

        return back()->withFragment('newsletter')->with('newsletter_status', 'Thanks for subscribing!');
    }
}
