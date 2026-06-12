<?php

namespace App\Mail;

use App\Models\Negotiation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NegotiationRejectedMail extends Mailable
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
        return $this->subject('Pengajuan Negosiasi Penawaran Belum Disetujui - Quick.Deal')
                    ->view('emails.negotiation-rejected');
    }
}
