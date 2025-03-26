<?php

namespace App\Filament\App\Pages\Auth;

use BetterFuturesStudio\FilamentLocalLogins\Concerns\HasLocalLogins;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class Login extends \Filament\Pages\Auth\Login
{
    use HasLocalLogins;

    /**
     * @throws RequestException
     * @throws BindingResolutionException
     * @throws ConnectionException
     */
    public function authenticate(): ?LoginResponse
    {

        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();

        if (!Filament::auth()->attempt($this->getCredentialsFromFormData($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }


        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (!$user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }


        ray('hi', $user->odk_id);

        if (!$user->odk_id) {

            $odkService = app()->make(OdkLinkService::class);
            $response = $odkService->createUser($this->getCredentialsFromFormData($data));

            // match user roles
            $user->update(['odk_id' => $response['id']]);
            $user->syncWithOdkCentral($response['id']);

        }


        return app(LoginResponse::class);
    }
}
