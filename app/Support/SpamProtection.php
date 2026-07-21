<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SpamProtection
{
    public static function validate(Request $request, int $minimumSeconds = 3): void
    {
        if ($request->filled('website')) {
            throw ValidationException::withMessages([
                'website' => 'We could not process this submission. Please try again.',
            ]);
        }

        $startedAt = (int) $request->input('form_started_at', 0);

        if ($startedAt > 0 && now()->timestamp - $startedAt < $minimumSeconds) {
            throw ValidationException::withMessages([
                'form_started_at' => 'Please take a moment to review the form before submitting.',
            ]);
        }
    }
}
