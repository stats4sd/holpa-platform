<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use Filament\Forms;
use Filament\Actions;
use Spatie\Permission\Models\Role;
use Awcodes\Shout\Components\Shout;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('invite users')
                ->form([
                    Shout::make('info')
                        ->type('info')
                        ->content('Add the email address(es) of the user(s) you would like to invite with a role. An invitation will be sent to each address.')
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('users')
                        ->label('Email Addresses to Invite')
                        ->schema([
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->required(),
                            Forms\Components\Select::make('role')
                                ->options(Role::all()->pluck('name', 'id'))
                                ->required()
                        ])
                        ->reorderable(false)
                        ->columns(2)
                        ->addActionLabel('Add Another Email Address')
                ])
                ->action(fn(array $data, ListRecords $livewire) => $this->handleInvitation($data)),
            Actions\CreateAction::make(),
        ];
    }

    public function handleInvitation(array $data): void
    {
        auth()->user()->sendInvites($data['users']);
    }
}
