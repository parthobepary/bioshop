<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentReceivedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Payment $payment
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Received - BioShop',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.payment-received',
            with: [
                'userName' => $this->user->name,
                'amount' => number_format($this->payment->amount),
                'method' => $this->payment->method,
                'transactionId' => $this->payment->transaction_id,
                'date' => $this->payment->created_at->format('F j, Y'),
                'billingUrl' => route('billing.index'),
            ],
        );
    }
}
