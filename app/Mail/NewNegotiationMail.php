<?php

namespace App\Mail;

use App\Models\Negotiation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewNegotiationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $negotiation;

    /**
     * Buat instance pesan baru.
     */
    public function __construct(Negotiation $negotiation)
    {
        $this->negotiation = $negotiation;
    }

    /**
     * Bangun pesan email.
     */
    public function build()
    {
        return $this->subject('Pengajuan Negosiasi Baru - Quick.Deal')
                    ->view('emails.new-negotiation');
    }
}
