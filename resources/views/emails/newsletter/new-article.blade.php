@php
    $articleUrl = \App\Support\HumelixLinks::url('/resources/'.$article->slug);
    $resourcesUrl = \App\Support\HumelixLinks::url('/resources');
    $homeUrl = \App\Support\HumelixLinks::websiteUrl();
    $unsubscribeUrl = \App\Support\HumelixLinks::url('/newsletter/unsubscribe/'.$subscriber->unsubscribe_token);
    $logoUrl = \App\Support\HumelixLinks::assetUrl('images/brand/humelix-logo-mark.png');
    $imageUrl = \App\Support\UchContent::emailImageUrl($article->featured_image_path, 'images/generated/safety/safety-toolbox-talks.jpg');
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $article->title }}</title>
</head>
<body style="margin:0;background:#f3f7fb;color:#102033;font-family:Arial,Helvetica,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;">New HUMELIX LIMITED resource: {{ $article->title }}</div>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f7fb;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:660px;background:#ffffff;border:1px solid #dce8f5;border-radius:22px;overflow:hidden;">
                    <tr>
                        <td style="padding:24px 28px;background:#06182c;color:#ffffff;">
                            <a href="{{ $homeUrl }}" style="display:inline-flex;align-items:center;gap:12px;color:#ffffff;text-decoration:none;">
                                <img src="{{ $logoUrl }}" width="46" height="46" alt="HUMELIX LIMITED" style="display:inline-block;border-radius:14px;background:#ffffff;">
                                <span style="display:inline-block;font-size:21px;font-weight:800;letter-spacing:.08em;">HUMELIX <span style="display:block;font-size:11px;letter-spacing:.35em;">LIMITED</span></span>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <img src="{{ $imageUrl }}" alt="{{ $article->title }}" width="660" style="display:block;width:100%;max-height:310px;object-fit:cover;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:34px 30px;">
                            <p style="margin:0 0 10px;color:#087df2;font-size:12px;font-weight:800;letter-spacing:.16em;text-transform:uppercase;">{{ $article->categoryLabel() }} Resource</p>
                            <h1 style="margin:0 0 14px;color:#0b1729;font-size:30px;line-height:1.14;">{{ $article->title }}</h1>
                            <p style="margin:0 0 22px;color:#51627a;font-size:16px;line-height:1.7;">{{ $article->excerpt }}</p>
                            <p style="margin:28px 0;">
                                <a href="{{ $articleUrl }}" style="display:inline-block;padding:15px 22px;border-radius:12px;background:#087df2;color:#ffffff;font-size:14px;font-weight:800;text-decoration:none;">Read Full Resource</a>
                                <a href="{{ $resourcesUrl }}" style="display:inline-block;margin-left:8px;padding:14px 20px;border:1px solid #b8d1ee;border-radius:12px;color:#087df2;font-size:14px;font-weight:800;text-decoration:none;">More Resources</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 30px;background:#f7fbff;border-top:1px solid #e4edf7;color:#71849a;font-size:12px;line-height:1.6;">
                            You are receiving this because you subscribed to HUMELIX LIMITED resource updates.<br>
                            <a href="{{ $homeUrl }}" style="color:#087df2;">Visit website</a> · <a href="{{ $unsubscribeUrl }}" style="color:#087df2;">Unsubscribe</a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
