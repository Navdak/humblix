<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\HumelixLinks;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    private array $fields = [
        'hero_headline' => 'textarea',
        'hero_subtext' => 'textarea',
        'founder_snapshot' => 'textarea',
        'footer_copyright' => 'text',
        'company_website_url' => 'url',
        'company_email' => 'email',
        'support_email' => 'email',
        'phone_primary' => 'text',
        'phone_secondary' => 'text',
        'whatsapp_number' => 'text',
        'facebook_url' => 'url',
        'twitter_url' => 'url',
        'linkedin_url' => 'url',
    ];

    private array $developerFields = [
        'developer_credit_enabled' => 'checkbox',
        'developer_credit_label' => 'text',
        'developer_credit_url' => 'url',
        'technical_partner_enabled' => 'checkbox',
        'technical_partner_name' => 'text',
        'technical_partner_title' => 'text',
        'technical_partner_brand' => 'text',
        'technical_partner_image_path' => 'text',
        'technical_partner_summary' => 'textarea',
        'technical_partner_about' => 'textarea',
        'technical_partner_portfolio_url' => 'url',
        'technical_partner_whatsapp' => 'text',
        'technical_partner_github_url' => 'url',
        'technical_partner_facebook_url' => 'url',
        'technical_partner_email' => 'email',
        'technical_partner_linkedin_url' => 'url',
        'technical_partner_extra_label' => 'text',
        'technical_partner_extra_url' => 'url',
    ];

    public function edit()
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::pluck('value','key')->toArray(),
            'fields' => $this->fields,
            'developerFields' => auth()->user()?->isSuperAdmin() ? $this->developerFields : [],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        if ($request->filled('company_website_url')) {
            $request->merge([
                'company_website_url' => HumelixLinks::normalizeWebsiteUrl((string) $request->input('company_website_url')),
            ]);
        }

        $data = $request->validate([
            'hero_headline' => ['required','string','max:255'],
            'hero_subtext' => ['required','string','max:500'],
            'founder_snapshot' => ['nullable','string','max:1000'],
            'footer_copyright' => ['nullable','string','max:255'],
            'company_website_url' => ['nullable','url','max:255'],
            'company_email' => ['nullable','email','max:160'],
            'support_email' => ['nullable','email','max:160'],
            'phone_primary' => ['nullable','string','max:80'],
            'phone_secondary' => ['nullable','string','max:80'],
            'whatsapp_number' => ['nullable','string','max:80'],
            'facebook_url' => ['nullable','url','max:255'],
            'twitter_url' => ['nullable','url','max:255'],
            'linkedin_url' => ['nullable','url','max:255'],
        ]);

        $developerData = [];
        if ($request->user()?->isSuperAdmin()) {
            $developerData = $request->validate([
                'developer_credit_label' => ['nullable','string','max:80'],
                'developer_credit_url' => ['nullable','url','max:255'],
                'technical_partner_name' => ['nullable','string','max:120'],
                'technical_partner_title' => ['nullable','string','max:140'],
                'technical_partner_brand' => ['nullable','string','max:100'],
                'technical_partner_image_path' => ['nullable','string','max:255'],
                'technical_partner_image' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
                'technical_partner_summary' => ['nullable','string','max:500'],
                'technical_partner_about' => ['nullable','string','max:1600'],
                'technical_partner_portfolio_url' => ['nullable','url','max:255'],
                'technical_partner_whatsapp' => ['nullable','string','max:120'],
                'technical_partner_github_url' => ['nullable','url','max:255'],
                'technical_partner_facebook_url' => ['nullable','url','max:255'],
                'technical_partner_email' => ['nullable','email','max:160'],
                'technical_partner_linkedin_url' => ['nullable','url','max:255'],
                'technical_partner_extra_label' => ['nullable','string','max:80'],
                'technical_partner_extra_url' => ['nullable','url','max:255'],
            ]);

            $developerData['developer_credit_enabled'] = $request->boolean('developer_credit_enabled') ? '1' : '0';
            $developerData['technical_partner_enabled'] = $request->boolean('technical_partner_enabled') ? '1' : '0';

            if ($request->hasFile('technical_partner_image')) {
                $previousImage = $developerData['technical_partner_image_path'] ?? null;
                if ($previousImage && ! str_starts_with($previousImage, 'images/')) {
                    Storage::disk('public')->delete($previousImage);
                }

                $developerData['technical_partner_image_path'] = $request->file('technical_partner_image')->store('technical-partner', 'public');
            }

            unset($developerData['technical_partner_image']);
        }

        foreach ($data as $key => $value) SiteSetting::setValue($key, $value, 'homepage');

        if ($developerData) {
            foreach ($developerData as $key => $value) {
                SiteSetting::setValue($key, $value, 'developer');
            }
        }

        return back()->with('success','Site settings updated.');
    }
}
