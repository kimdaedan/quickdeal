<?php

namespace App\Mail;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $payment;

    /**
     * Create a new message instance.
     */
    public function __construct(Invoice $invoice, InvoicePayment $payment)
    {
        $this->invoice = $invoice;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Pembayaran Invoice Ditolak - Quick.Deal')
                    ->view('emails.payment-rejected');
    }
}
