<?php

namespace App\Mail;

use App\Models\Negotiation;
use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NegotiationApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $negotiation;
    public $invoice;

    /**
     * Buat instance pesan baru.
     */
    public function __construct(Negotiation $negotiation, Invoice $invoice)
    {
        $this->negotiation = $negotiation;
        $this->invoice = $invoice;
    }

    /**
     * Bangun pesan email.
     */
    public function build()
    {
        return $this->subject('Pengajuan Negosiasi Disetujui - Quick.Deal')
                    ->view('emails.negotiation-approved');
    }
}
