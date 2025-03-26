<?php

namespace App\Filament\App\Pages\Auth;

use Awcodes\Shout\Components\Shout;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Stats4sd\FilamentOdkLink\Models\OdkLink\Interfaces\WithOdkCentralAccount;
use Stats4sd\FilamentOdkLink\Services\OdkLinkService;

class EditProfile extends \Filament\Pages\Auth\EditProfile
{
    /**
     * @throws \Exception
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Section::make('Profile Information')
                            ->schema([
                                $this->getNameFormComponent(),
                                $this->getEmailFormComponent(),
                            ]),
                        Section::make('Change Password')
                            ->columns(1)
                            ->schema([
                                Shout::make('password-info')
                                    ->content('To change your password, please first enter your current password, then the new password. You may leave the password fields blank if you do not wish to change your password.'),
                                $this->getCurrentPasswordFormComponent(),
                                $this->getPasswordFormComponent(),
                                $this->getPasswordConfirmationFormComponent(),
                            ]),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(!static::isSimple()),
            ),
        ];
    }

    protected function getCurrentPasswordFormComponent(): Component
    {
        return TextInput::make('current_password')
            ->label(__('Current Password'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->requiredWith('password')
            ->rule('current_password')
            ->autocomplete('current-password');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/edit-profile.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->rule(Password::default())
            ->minLength(10)
            ->autocomplete('new-password')
            ->same('passwordConfirmation');
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::pages/auth/edit-profile.form.password_confirmation.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {

        $data['password_plain'] = $data['password'];
        $data['password'] = Hash::make($data['password']);

        return $data;
    }

    /**
     * @throws RequestException
     * @throws BindingResolutionException
     * @throws ConnectionException
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {

        $updateData = [
            'email' => $data['email'],
            'name' => $data['name'],
        ];

        $record->update($updateData);

        if (isset($data['password']) && $data['password'] !== '') {

            $record->update(['password' => $data['password']]);

            if ($record instanceof WithOdkCentralAccount) {
                $odkLinkService = app()->make(OdkLinkService::class);
                $odkLinkService->updateUserPassword($record, $data['current_password'], $data['password_plain']);
            }

        }
        return $record;

    }
}
