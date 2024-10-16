<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;
use Symfony\Component\HttpFoundation\Response;

class AddProgramAdminMenuItems
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        logger("AddProgramAdminMenuItems.handle()...");

        if (auth()->user()) {

            logger('users.latest_program_id: ' . auth()->user()->latest_program_id);

            filament()->getCurrentPanel()->userMenuItems([
                MenuItem::make()
                    ->label('Profile')
                    ->url(url('/program/1/programs/1')),
            ]);
        }

        return $next($request);
    }
}
