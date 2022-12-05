<?php

namespace Laravel\Jetstream\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Laravel\Jetstream\CompanyInvitation as CompanyInvitationModel;

class CompanyInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The company invitation instance.
     *
     * @var \Laravel\Jetstream\CompanyInvitation
     */
    public $invitation;

    /**
     * Create a new message instance.
     *
     * @param  \Laravel\Jetstream\CompanyInvitation  $invitation
     * @return void
     */
    public function __construct(CompanyInvitationModel $invitation)
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
        return $this->markdown('jetstream::mail.company-invitation', ['acceptUrl' => URL::signedRoute('company-invitations.accept', [
            'invitation' => $this->invitation,
        ])])->subject(__('Company Invitation'));
    }
}
