@php
    $settings = \App\Models\SiteSetting::pluck('value', 'key')->toArray();
    $homeUrl = \App\Support\HumelixLinks::websiteUrl();
    $logoUrl = \App\Support\HumelixLinks::assetUrl('images/brand/humelix-logo-mark.png');
    $operationsName = $settings['assignment_contact_name'] ?? 'HUMELIX Operations Team';
    $operationsPhone = $settings['assignment_contact_phone'] ?? ($settings['phone_primary'] ?? null);
    $operationsWhatsapp = $settings['assignment_contact_whatsapp'] ?? ($settings['whatsapp_number'] ?? null);
    $operationsEmail = $settings['assignment_contact_email'] ?? ($settings['support_email'] ?? ($settings['company_email'] ?? config('mail.from.address')));
    $operationsNote = $settings['assignment_contact_note'] ?? 'Contact HUMELIX Operations before visiting any client site to confirm schedule, exact location and client readiness.';
    $siteAddress = $enquiry->siteAddressForEngineer();
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HUMELIX Assignment</title>
</head>
<body style="margin:0;background:#f3f7fb;color:#102033;font-family:Arial,Helvetica,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;">You have been assigned to a HUMELIX LIMITED enquiry. Contact Operations before visiting site.</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f7fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border:1px solid #dce8f5;border-radius:22px;overflow:hidden;">
                    <tr>
                        <td style="padding:24px 28px;background:#06182c;color:#ffffff;">
                            <a href="{{ $homeUrl }}" style="display:inline-flex;align-items:center;gap:12px;color:#ffffff;text-decoration:none;">
                                <img src="{{ $logoUrl }}" width="46" height="46" alt="HUMELIX LIMITED" style="display:inline-block;border-radius:14px;background:#ffffff;">
                                <span style="display:inline-block;font-size:21px;font-weight:800;letter-spacing:.08em;">HUMELIX <span style="display:block;font-size:11px;letter-spacing:.35em;">LIMITED</span></span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 30px;">
                            <p style="margin:0 0 10px;color:#087df2;font-size:12px;font-weight:800;letter-spacing:.16em;text-transform:uppercase;">Engineer Assignment</p>
                            <h1 style="margin:0 0 14px;color:#0b1729;font-size:28px;line-height:1.16;">Hello {{ $engineer->name }}, you have a new assigned enquiry.</h1>
                            <p style="margin:0 0 20px;color:#51627a;font-size:16px;line-height:1.7;">Please review the assignment summary below. This is not a movement approval.</p>

                            <div style="margin:0 0 22px;padding:16px 18px;border:1px solid #fed7aa;border-radius:16px;background:#fff7ed;color:#7c2d12;">
                                <strong style="display:block;margin-bottom:6px;color:#9a3412;">Important site-visit rule</strong>
                                <span style="font-size:14px;line-height:1.65;">Do not visit the client site until HUMELIX Operations confirms the schedule, exact site location, client readiness, access requirements and any safety/materials instructions.</span>
                            </div>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;background:#f7fbff;border:1px solid #dce8f5;border-radius:16px;overflow:hidden;">
                                <tr>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;color:#51627a;">Reference</td>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;font-weight:700;">{{ $enquiry->reference_number }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;color:#51627a;">Type of Work</td>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;font-weight:700;">{{ $enquiry->display_type_of_work }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;color:#51627a;">Building</td>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;font-weight:700;">{{ $enquiry->building_type ?: 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;color:#51627a;">Area / City</td>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;font-weight:700;">{{ $enquiry->country ?: 'Country not set' }}{{ $enquiry->state_city ? ' · '.$enquiry->state_city : '' }}{{ $enquiry->display_location ? ' · '.$enquiry->display_location : '' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;color:#51627a;">Site Address</td>
                                    <td style="padding:14px 18px;border-bottom:1px solid #dce8f5;font-weight:700;">{{ $siteAddress }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 18px;color:#51627a;">Urgency</td>
                                    <td style="padding:14px 18px;font-weight:700;">{{ $enquiry->urgency ?: 'Normal' }}</td>
                                </tr>
                            </table>

                            <div style="margin-top:22px;padding:18px;border:1px solid #cfe2fb;border-radius:16px;background:#f7fbff;">
                                <strong style="display:block;margin-bottom:8px;color:#0b1729;">Contact HUMELIX Operations</strong>
                                <p style="margin:0 0 12px;color:#51627a;font-size:14px;line-height:1.65;">{{ $operationsNote }}</p>
                                <p style="margin:0;color:#102033;font-size:14px;line-height:1.8;">
                                    <strong>{{ $operationsName }}</strong><br>
                                    @if($operationsPhone) Call: {{ $operationsPhone }}<br>@endif
                                    @if($operationsWhatsapp) WhatsApp: {{ $operationsWhatsapp }}<br>@endif
                                    @if($operationsEmail) Email: {{ $operationsEmail }}<br>@endif
                                    Website: {{ $homeUrl }}
                                </p>
                            </div>

                            @if($engineer->linked_user_id && Route::has('admin.enquiries.show'))
                                <p style="margin:28px 0 0;">
                                    <a href="{{ route('admin.enquiries.show', $enquiry) }}" style="display:inline-block;padding:15px 22px;border-radius:12px;background:#087df2;color:#ffffff;font-size:14px;font-weight:800;text-decoration:none;">Open Assignment in Admin</a>
                                </p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 30px;background:#f7fbff;border-top:1px solid #e4edf7;color:#71849a;font-size:12px;line-height:1.6;">
                            This is an internal HUMELIX LIMITED assignment notice. If this was not meant for you, contact HUMELIX Operations.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
