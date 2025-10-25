<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\MobileAuthService;

class MobileGuest
{
    public function __construct(
        private readonly MobileAuthService $authService
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->authService->check()) {
            return redirect()->route('mobile.dashboard');
        }

        return $next($request);
    }
}