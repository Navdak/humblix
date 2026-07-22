@php
    $websiteUrl = \App\Support\HumelixLinks::websiteUrl();
    $resourcesUrl = \App\Support\HumelixLinks::url('/resources');
    $unsubscribeUrl = \App\Support\HumelixLinks::url('/newsletter/unsubscribe/'.$subscriber->unsubscribe_token);
    $logoUrl = \App\Support\HumelixLinks::assetUrl('images/brand/humelix-logo-mark.png');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to HUMELIX LIMITED resource updates</title>
</head>
<body style="margin:0;background:#f3f7fb;color:#102033;font-family:Arial,Helvetica,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;">You are now subscribed to HUMELIX LIMITED engineering resources.</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f7fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border:1px solid #dce8f5;border-radius:22px;overflow:hidden;">
                    <tr>
                        <td style="padding:28px 30px 20px;background:#06182c;color:#ffffff;">
                            <a href="{{ $websiteUrl }}" style="display:inline-flex;align-items:center;gap:12px;color:#ffffff;text-decoration:none;">
                                <img src="{{ $logoUrl }}" width="48" height="48" alt="HUMELIX LIMITED" style="display:inline-block;border-radius:14px;background:#ffffff;">
                                <span style="display:inline-block;font-size:22px;font-weight:800;letter-spacing:.08em;">HUMELIX <span style="display:block;font-size:11px;letter-spacing:.35em;">LIMITED</span></span>
                            </a>
                            <p style="margin:22px 0 0;color:#9fc6ef;font-size:12px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;">Engineering Comfort. Powering Reliability.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 30px;">
                            <h1 style="margin:0 0 14px;color:#0b1729;font-size:28px;line-height:1.15;">You’re subscribed</h1>
                            <p style="margin:0 0 18px;color:#51627a;font-size:16px;line-height:1.7;">Thanks for joining HUMELIX LIMITED resource updates. We’ll send you practical HVAC, solar, electrical, safety, maintenance and vendor/equipment guides when new resources are published.</p>
                            <p style="margin:26px 0;">
                                <a href="{{ $resourcesUrl }}" style="display:inline-block;padding:15px 22px;border-radius:12px;background:#087df2;color:#ffffff;font-size:14px;font-weight:800;text-decoration:none;">Explore Resources</a>
                            </p>
                            <p style="margin:0;color:#6b7b92;font-size:13px;line-height:1.65;">If you did not request this subscription, you can unsubscribe immediately using the link below.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 30px;background:#f7fbff;border-top:1px solid #e4edf7;color:#71849a;font-size:12px;line-height:1.6;">
                            <strong style="color:#0b1729;">HUMELIX LIMITED</strong><br>
                            <a href="{{ $websiteUrl }}" style="color:#087df2;">Visit our website</a> · <a href="{{ $unsubscribeUrl }}" style="color:#087df2;">Unsubscribe</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
