<x-mail::message>
# Your Subscription is Now Active! 🎉

Hi {{ $userName }},

Great news! Your **{{ $planName }}** subscription has been activated successfully.

## Subscription Details:

<x-mail::table>
| Detail | Value |
|:-------|:------|
| Plan | {{ $planName }} |
| Status | Active |
@if($expiresAt)
| Valid Until | {{ $expiresAt }} |
@endif
</x-mail::table>

## What's Unlocked:

@if(str_contains(strtolower($planName), 'starter'))
- Up to 20 products
- 20 custom links
- Basic analytics
- WhatsApp integration
@elseif(str_contains(strtolower($planName), 'pro'))
- Unlimited products
- Unlimited links
- Advanced analytics
- AI-powered WhatsApp assistant
- Priority support
@elseif(str_contains(strtolower($planName), 'business'))
- Everything in Pro
- Multiple team members
- Custom domain support
- API access
- Dedicated support
@endif

<x-mail::button :url="$dashboardUrl">
Go to Dashboard
</x-mail::button>

Thank you for choosing BioShop. If you have any questions about your subscription, visit your billing page or contact our support team.

Best regards,<br>
The BioShop Team

<x-mail::subcopy>
You can manage your subscription anytime from your [billing page]({{ $billingUrl }}).
</x-mail::subcopy>
</x-mail::message>
