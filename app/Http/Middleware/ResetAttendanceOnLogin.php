<?php

namespace App\Http\Middleware;

use App\Models\AttendanceRecord;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResetAttendanceOnLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'student') {
            AttendanceRecord::where('user_id', Auth::id())->delete();
        }

        return $next($request);
    }
}
