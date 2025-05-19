<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Stats4sd\FilamentTeamManagement\Events\RegisteredWithData;

class RegisterNewUserToOdkCentral
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(RegisteredWithData $event): void
    {
        /** @var User $user */
        $user = $event->user;

        ray('registering to ODK Central with email: ' . $event->user->email);
        ray('registering to ODK Central with password: ' . $event->data['original_password']);

        try {
            $user->registerOnOdkCentral($event->data['original_password']);
        } catch (\Throwable $e) {
            Log::error($e);
        }
    }
}
