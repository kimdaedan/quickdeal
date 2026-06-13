<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\User;
use App\Mail\PaymentProofUploadedMail;
use App\Mail\PaymentApprovedMail;
use App\Mail\PaymentRejectedMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PaymentEmailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that payment mailables can be successfully rendered.
     */
    public function test_mailables_can_be_rendered(): void
    {
        $user = User::factory()->create([
            'role' => 'client',
            'name' => 'John Client',
            'email' => 'client@example.com'
        ]);

        $invoice = Invoice::create([
            'offer_id' => 1,
            'no_invoice' => 'INV-TEST-001',
            'nama_klien' => 'John Client',
            'total_penawaran' => 100000,
            'total_tambahan' => 0,
            'diskon' => 0,
            'grand_total' => 100000,
            'total_dp' => 0,
            'sisa_pembayaran' => 100000,
            'status' => 'due',
        ]);

        $payment = InvoicePayment::create([
            'invoice_id' => $invoice->id,
            'keterangan' => 'DP Pembayaran',
            'jumlah' => 0,
            'bukti_transfer' => 'bukti_transfer/test.jpg',
            'status_verifikasi' => 'pending',
        ]);

        $mail1 = new PaymentProofUploadedMail($invoice, $payment);
        $this->assertNotEmpty($mail1->render());

        $mail2 = new PaymentApprovedMail($invoice, $payment);
        $this->assertNotEmpty($mail2->render());

        $mail3 = new PaymentRejectedMail($invoice, $payment);
        $this->assertNotEmpty($mail3->render());
    }
}
