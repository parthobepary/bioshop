<x-mail::message>
# Payment Received

Hi {{ $userName }},

We've received your payment. Thank you for your support!

## Payment Details:

<x-mail::table>
| Detail | Value |
|:-------|:------|
| Amount | ৳{{ $amount }} |
| Method | {{ ucfirst($method) }} |
| Transaction ID | {{ $transactionId }} |
| Date | {{ $date }} |
| Status | Pending Review |
</x-mail::table>

Our team will review your payment and activate your subscription within 24 hours. You'll receive another email once your subscription is active.

<x-mail::button :url="$billingUrl">
View Billing History
</x-mail::button>

If you have any questions about your payment, please contact our support team.

Thank you,<br>
The BioShop Team
</x-mail::message>
