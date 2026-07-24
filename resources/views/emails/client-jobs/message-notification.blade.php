@php
    $homeUrl = \App\Support\HumelixLinks::websiteUrl();
    $portalUrl = $clientJob->portalUrl();
    $logoUrl = \App\Support\HumelixLinks::assetUrl('images/brand/humelix-logo-mark.png');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HUMELIX Job Update</title>
</head>
<body style="margin:0;background:#f3f7fb;color:#102033;font-family:Arial,Helvetica,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;">There is a new update on your HUMELIX job {{ $clientJob->job_reference }}.</div>
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
                            <p style="margin:0 0 10px;color:#087df2;font-size:12px;font-weight:800;letter-spacing:.16em;text-transform:uppercase;">Job Update</p>
                            <h1 style="margin:0 0 14px;color:#0b1729;font-size:28px;line-height:1.16;">New message on {{ $clientJob->job_reference }}</h1>
                            <p style="margin:0 0 20px;color:#51627a;font-size:16px;line-height:1.7;">HUMELIX has added an update to your private job portal.</p>

                            <div style="margin:0 0 22px;padding:16px 18px;border:1px solid #dce8f5;border-radius:16px;background:#f7fbff;color:#102033;">
                                <strong style="display:block;margin-bottom:8px;">{{ $message->senderLabel() }}</strong>
                                <p style="margin:0;color:#51627a;font-size:15px;line-height:1.7;">{{ \Illuminate\Support\Str::limit($message->body, 260) }}</p>
                                @if($message->attachments->isNotEmpty())
                                    <p style="margin:12px 0 0;color:#0b1729;font-size:13px;line-height:1.6;font-weight:700;">{{ $message->attachments->count() }} file attachment{{ $message->attachments->count() === 1 ? '' : 's' }} added. Open the private portal to view or download.</p>
                                @endif
                            </div>

                            <p style="margin:28px 0;">
                                <a href="{{ $portalUrl }}" style="display:inline-block;padding:15px 22px;border-radius:12px;background:#087df2;color:#ffffff;font-size:14px;font-weight:800;text-decoration:none;">Open Job Portal</a>
                            </p>
                            <p style="margin:0;color:#6b7b92;font-size:13px;line-height:1.65;">Keep this link private. It opens only your HUMELIX job conversation.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 30px;background:#f7fbff;border-top:1px solid #e4edf7;color:#71849a;font-size:12px;line-height:1.6;">
                            HUMELIX LIMITED · <a href="{{ $homeUrl }}" style="color:#087df2;">Visit website</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
