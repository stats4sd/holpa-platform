<?php

namespace App\Livewire;

use App\Mail\RegisterInterestEmail;
use App\Mail\RegisterInterestEmailResponse;
use Awcodes\Shout\Components\Shout;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CoverPage extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function render(): Factory|Application|View|\Illuminate\View\View|null
    {
        return view('livewire.cover-page');
    }

    public function registerInterestAction(): Action
    {
        return Action::make('registerInterest')
            ->extraAttributes(['class' => 'buttonb px-4 mx-2'])
            ->label('Register Interest')
            ->form([
                Shout::make('message')
                    ->content('Thanks you for your interest in the HOLPA online tool. Please fill in your details below - your email address will be used to contact you.'),
                TextInput::make('email')->required()
                    ->email()
                    ->label('Enter you email address'),
                TextInput::make('name')
                    ->label('Enter your name'),
                Textarea::make('organisation')
                    ->label('Enter your organisation name'),
                Textarea::make('details')
                    ->rows(5)
                    ->label('Do you intend to impliment HOLPA? If so, please give some details about your project / work, etc.'),
            ])
            ->action(function (array $data) {
                // send an email to the support email address

                Mail::to(config('mail.to.support'))->send(new RegisterInterestEmail($data));
                Mail::to($data['email'])->send(new RegisterInterestEmailResponse($data));

                ray('hi');
            });

    }


}
