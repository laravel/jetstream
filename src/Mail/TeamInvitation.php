<?php

namespace Laravel\Jetstream\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Model;

class TeamInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The team invitation instance.
     *
     * @var Model
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @param  Model  $invitation
     * @return void
     */
    public function __construct(Model $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('jetstream::mail.team-invitation', ['acceptUrl' => URL::signedRoute('team-invitations.accept', [
            'invitation' => $this->invitation,
        ])])->subject(__('Team Invitation'));
    }
}
