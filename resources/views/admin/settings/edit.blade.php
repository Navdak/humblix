@extends('layouts.admin')
@section('title','Site Settings')
@section('page_title','Site Settings')
@section('page_subtitle','Update public brand copy, contact details, and social profiles.')
@section('content')
<form class="admin-card settings-form" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="settings-jump-links" aria-label="Settings sections">
        <a href="#homepage-settings">Homepage</a>
        <a href="#contact-settings">Contact</a>
        @if(!empty($developerFields))<a href="#developer-settings">Developer</a>@endif
    </div>
    <div id="homepage-settings"></div>
    <div class="section-head left" style="margin-bottom:24px"><span class="eyebrow">Homepage Copy</span><h2 class="section-title" style="font-size:32px">Edit public brand content without touching code.</h2></div>
    <div class="form-grid">
        @foreach($fields as $key => $type)
            @if($key === 'company_email')<div id="contact-settings" class="settings-anchor"></div>@endif
            <div class="form-field {{ str_contains($key, 'headline') || str_contains($key, 'subtext') || str_contains($key, 'snapshot') ? 'full' : '' }}">
                <label>{{ ucwords(str_replace('_',' ', $key)) }}</label>
                @if($type === 'textarea')
                    <textarea name="{{ $key }}" rows="{{ $key === 'founder_snapshot' ? 5 : 3 }}">{{ old($key, $settings[$key] ?? '') }}</textarea>
                @else
                    <input type="{{ $type }}" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}">
                    @if($key === 'company_website_url')<small>Use only the main website URL. Newsletter article links are generated automatically.</small>@endif
                @endif
            </div>
        @endforeach
    </div>
    @if(!empty($developerFields))
        <div id="developer-settings"></div>
        <div class="admin-note" style="margin-top:22px">
            <strong>Developer credit:</strong> These fields are visible to Technical Super Admin only and control the subtle public/admin credit for the website developer.
        </div>
        <div class="form-grid" style="margin-top:16px">
            <label class="form-field full" style="display:flex;align-items:center;flex-direction:row;gap:10px">
                <input type="checkbox" name="developer_credit_enabled" value="1" @checked(old('developer_credit_enabled', $settings['developer_credit_enabled'] ?? '1') !== '0') style="width:auto">
                <span>Show developer credit in the website footer and admin login</span>
            </label>
            <div class="form-field">
                <label>Developer Credit Label</label>
                <input type="text" name="developer_credit_label" value="{{ old('developer_credit_label', $settings['developer_credit_label'] ?? 'Navdak Digital') }}">
            </div>
            <div class="form-field">
                <label>Developer Portfolio URL</label>
                <input type="url" name="developer_credit_url" value="{{ old('developer_credit_url', $settings['developer_credit_url'] ?? '') }}" placeholder="https://your-portfolio.example">
            </div>
        </div>
        <div class="admin-note" style="margin-top:22px">
            <strong>Technical Partner Profile:</strong> This controls the folded partner card on the public Team page. It remains editable by Technical Super Admin only.
        </div>
        <div class="form-grid" style="margin-top:16px">
            <label class="form-field full" style="display:flex;align-items:center;flex-direction:row;gap:10px">
                <input type="checkbox" name="technical_partner_enabled" value="1" @checked(old('technical_partner_enabled', $settings['technical_partner_enabled'] ?? '1') !== '0') style="width:auto">
                <span>Show Technical Partner card on the public Team page</span>
            </label>
            <div class="form-field"><label>Technical Partner Name</label><input type="text" name="technical_partner_name" value="{{ old('technical_partner_name', $settings['technical_partner_name'] ?? 'Ikechukwu Prince Onyebuchi') }}"></div>
            <div class="form-field"><label>Role / Title</label><input type="text" name="technical_partner_title" value="{{ old('technical_partner_title', $settings['technical_partner_title'] ?? 'Website Developer & Platform Maintainer') }}"></div>
            <div class="form-field"><label>Brand / Company</label><input type="text" name="technical_partner_brand" value="{{ old('technical_partner_brand', $settings['technical_partner_brand'] ?? 'Navdak Digital') }}"></div>
            <div class="form-field">
                <label>Technical Partner Image</label>
                @php($technicalPartnerImagePath = old('technical_partner_image_path', $settings['technical_partner_image_path'] ?? 'images/generated/careers/careers-office-admin-culture.jpg'))
                <input type="hidden" name="technical_partner_image_path" value="{{ $technicalPartnerImagePath }}">
                @if($technicalPartnerImagePath)
                    <div class="image-frame" style="max-width:180px;aspect-ratio:4/5;margin-bottom:10px">
                        <img src="{{ \App\Support\UchContent::imageUrl($technicalPartnerImagePath, 'images/generated/careers/careers-office-admin-culture.jpg') }}" alt="Current technical partner image">
                    </div>
                @endif
                <input type="file" name="technical_partner_image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
                <small>Upload your photo from this device. JPG, PNG or WebP only, max 5MB. If empty, the current image stays.</small>
            </div>
            <div class="form-field"><label>Portfolio URL</label><input type="url" name="technical_partner_portfolio_url" value="{{ old('technical_partner_portfolio_url', $settings['technical_partner_portfolio_url'] ?? '') }}" placeholder="https://your-portfolio.example"><small>If this is empty, the portfolio button is hidden publicly.</small></div>
            <div class="form-field full"><label>Short Intro</label><textarea name="technical_partner_summary" rows="3">{{ old('technical_partner_summary', $settings['technical_partner_summary'] ?? 'I design and maintain modern business websites, admin dashboards and digital platforms that are clean, scalable and easy for teams to manage.') }}</textarea><small>This is the brief text shown before visitors click Read More.</small></div>
            <div class="form-field full"><label>About / Expanded Text</label><textarea name="technical_partner_about" rows="6">{{ old('technical_partner_about', $settings['technical_partner_about'] ?? 'I am a website developer and platform maintainer focused on building reliable business systems, admin dashboards, automation-ready workflows and deployment-ready digital platforms. For HUMELIX LIMITED, Navdak Digital delivered the public website structure, editable admin dashboard, visitor analytics foundation, SEO setup, generated visual assets and Render preview deployment workflow.') }}</textarea><small>This appears only after the visitor opens the Technical Partner card.</small></div>
            <div class="form-field"><label>WhatsApp Link or Number</label><input type="text" name="technical_partner_whatsapp" value="{{ old('technical_partner_whatsapp', $settings['technical_partner_whatsapp'] ?? '') }}" placeholder="https://wa.me/2349000000000 or +234..."></div>
            <div class="form-field"><label>GitHub URL</label><input type="url" name="technical_partner_github_url" value="{{ old('technical_partner_github_url', $settings['technical_partner_github_url'] ?? '') }}" placeholder="https://github.com/navdak"></div>
            <div class="form-field"><label>Facebook URL</label><input type="url" name="technical_partner_facebook_url" value="{{ old('technical_partner_facebook_url', $settings['technical_partner_facebook_url'] ?? '') }}" placeholder="https://facebook.com/navdakdigital"></div>
            <div class="form-field"><label>Email</label><input type="email" name="technical_partner_email" value="{{ old('technical_partner_email', $settings['technical_partner_email'] ?? '') }}" placeholder="developer@example.com"></div>
            <div class="form-field"><label>LinkedIn URL</label><input type="url" name="technical_partner_linkedin_url" value="{{ old('technical_partner_linkedin_url', $settings['technical_partner_linkedin_url'] ?? '') }}" placeholder="https://linkedin.com/in/navdak"></div>
            <div class="form-field"><label>Extra Link Label</label><input type="text" name="technical_partner_extra_label" value="{{ old('technical_partner_extra_label', $settings['technical_partner_extra_label'] ?? '') }}" placeholder="Book a call"></div>
            <div class="form-field"><label>Extra Link URL</label><input type="url" name="technical_partner_extra_url" value="{{ old('technical_partner_extra_url', $settings['technical_partner_extra_url'] ?? '') }}"></div>
        </div>
    @endif
    <button class="btn btn-primary settings-save-main" style="margin-top:20px">Save Settings</button>
    <div class="settings-sticky-save" aria-label="Save settings">
        <button class="btn btn-primary" type="submit">Save Settings</button>
    </div>
</form>
@endsection
