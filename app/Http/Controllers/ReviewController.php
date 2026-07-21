<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\Review;
use App\Support\SpamProtection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        SpamProtection::validate($request, 4);

        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:150'],
            'client_role' => ['nullable', 'string', 'max:150'],
            'company' => ['nullable', 'string', 'max:150'],
            'location' => ['nullable', 'string', 'max:150'],
            'project_category' => ['nullable', 'string', 'max:150'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'min:20', 'max:1500'],
            'review_consent' => ['accepted'],
        ], [
            'review_consent.accepted' => 'Please confirm that HUMELIX LIMITED can review and publish your feedback.',
        ]);

        unset($data['review_consent']);

        $review = Review::create([
            ...$data,
            'is_approved' => false,
        ]);

        AdminNotification::createForReview($review);

        return back()->with('success', 'Thank you for your feedback. Your review has been submitted for moderation.');
    }
}
