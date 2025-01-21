<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GroupInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $group;
    public $inviter;

    public function __construct($token, $group, $inviter)
    {
        $this->token = $token;
        $this->group = $group;
        $this->inviter = $inviter;
    }

    public function build()
    {
        return $this->view('emails.group-invitation')
                    ->subject('Invitation to join a group');
    }
}
