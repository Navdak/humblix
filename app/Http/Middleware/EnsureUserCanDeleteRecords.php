<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanDeleteRecords
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('DELETE')) {
            abort_unless(
                $request->user()?->canDeleteRecords(),
                403,
                'Only the Company Owner and Technical Super Admin can delete records.'
            );
        }

        return $next($request);
    }
}
