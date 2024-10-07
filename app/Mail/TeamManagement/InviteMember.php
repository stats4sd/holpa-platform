<?php

namespace App\Mail\TeamManagement;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use App\Models\TeamManagement\TeamInvite;

class InviteMember extends Mailable
{
    use Queueable;
    use SerializesModels;

    public TeamInvite $invite;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(TeamInvite $invite)
    {
        $this->invite = $invite;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: config('mail.from.address'),
            subject: config('app.name') . ': Invitation To Join Team ' . $this->invite->team->name,
        );
    }

    /**
     * Get the message content definition
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.team_invite',
            with: [
                'acceptUrl' => URL::signedRoute(
                    'filament.app.register',
                    [
                        'token' => $this->invite->token,
                    ],
                ),
            ],
        );
    }
}
