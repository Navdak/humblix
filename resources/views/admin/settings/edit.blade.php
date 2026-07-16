@extends('layouts.admin')
@section('title','Site Settings')
@section('page_title','Site Settings')
@section('page_subtitle','Update public brand copy, contact details, and social profiles.')
@section('content')
<form class="admin-card" method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')
    <div class="section-head left" style="margin-bottom:24px"><span class="eyebrow">Homepage Copy</span><h2 class="section-title" style="font-size:32px">Edit public brand content without touching code.</h2></div>
    <div class="form-grid">
        @foreach($fields as $key => $type)
            <div class="form-field {{ str_contains($key, 'headline') || str_contains($key, 'subtext') || str_contains($key, 'snapshot') ? 'full' : '' }}">
                <label>{{ ucwords(str_replace('_',' ', $key)) }}</label>
                @if($type === 'textarea')
                    <textarea name="{{ $key }}" rows="{{ $key === 'founder_snapshot' ? 5 : 3 }}">{{ old($key, $settings[$key] ?? '') }}</textarea>
                @else
                    <input type="{{ $type }}" name="{{ $key }}" value="{{ old($key, $settings[$key] ?? '') }}">
                @endif
            </div>
        @endforeach
    </div>
    @if(!empty($developerFields))
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
            <div class="form-field"><label>Portfolio URL</label><input type="url" name="technical_partner_portfolio_url" value="{{ old('technical_partner_portfolio_url', $settings['technical_partner_portfolio_url'] ?? '') }}"></div>
            <div class="form-field full"><label>Short Intro</label><textarea name="technical_partner_summary" rows="3">{{ old('technical_partner_summary', $settings['technical_partner_summary'] ?? 'Navdak Digital designed and developed the HUMELIX LIMITED website and admin platform.') }}</textarea></div>
            <div class="form-field full"><label>About / Expanded Text</label><textarea name="technical_partner_about" rows="6">{{ old('technical_partner_about', $settings['technical_partner_about'] ?? 'I support businesses with modern websites, dashboards, admin systems, automation tools and deployment-ready digital platforms. For HUMELIX LIMITED, Navdak Digital delivered the public website structure, content management dashboard, visitor analytics foundation, SEO setup and deployment support.') }}</textarea></div>
            <div class="form-field"><label>WhatsApp Link or Number</label><input type="text" name="technical_partner_whatsapp" value="{{ old('technical_partner_whatsapp', $settings['technical_partner_whatsapp'] ?? '') }}" placeholder="https://wa.me/234... or +234..."></div>
            <div class="form-field"><label>GitHub URL</label><input type="url" name="technical_partner_github_url" value="{{ old('technical_partner_github_url', $settings['technical_partner_github_url'] ?? '') }}"></div>
            <div class="form-field"><label>Facebook URL</label><input type="url" name="technical_partner_facebook_url" value="{{ old('technical_partner_facebook_url', $settings['technical_partner_facebook_url'] ?? '') }}"></div>
            <div class="form-field"><label>Email</label><input type="email" name="technical_partner_email" value="{{ old('technical_partner_email', $settings['technical_partner_email'] ?? '') }}"></div>
            <div class="form-field"><label>LinkedIn URL</label><input type="url" name="technical_partner_linkedin_url" value="{{ old('technical_partner_linkedin_url', $settings['technical_partner_linkedin_url'] ?? '') }}"></div>
            <div class="form-field"><label>Extra Link Label</label><input type="text" name="technical_partner_extra_label" value="{{ old('technical_partner_extra_label', $settings['technical_partner_extra_label'] ?? '') }}" placeholder="Book a call"></div>
            <div class="form-field"><label>Extra Link URL</label><input type="url" name="technical_partner_extra_url" value="{{ old('technical_partner_extra_url', $settings['technical_partner_extra_url'] ?? '') }}"></div>
        </div>
    @endif
    <button class="btn btn-primary" style="margin-top:20px">Save Settings</button>
</form>
@endsection
