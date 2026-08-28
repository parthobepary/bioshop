<?php

namespace App\Mail;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionActivatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public Subscription $subscription
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your BioShop Subscription is Active! 🚀',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.subscription-activated',
            with: [
                'userName' => $this->user->name,
                'planName' => $this->subscription->plan->name,
                'expiresAt' => $this->subscription->ends_at?->format('F j, Y'),
                'dashboardUrl' => route('dashboard'),
                'billingUrl' => route('billing.index'),
            ],
        );
    }
}
