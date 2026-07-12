<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private array $fields = [
        'hero_headline' => 'textarea',
        'hero_subtext' => 'textarea',
        'founder_snapshot' => 'textarea',
        'footer_copyright' => 'text',
        'company_email' => 'email',
        'support_email' => 'email',
        'phone_primary' => 'text',
        'phone_secondary' => 'text',
        'whatsapp_number' => 'text',
        'facebook_url' => 'url',
        'twitter_url' => 'url',
        'linkedin_url' => 'url',
    ];

    public function edit()
    {
        return view('admin.settings.edit', ['settings' => SiteSetting::pluck('value','key')->toArray(), 'fields' => $this->fields]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'hero_headline' => ['required','string','max:255'],
            'hero_subtext' => ['required','string','max:500'],
            'founder_snapshot' => ['nullable','string','max:1000'],
            'footer_copyright' => ['nullable','string','max:255'],
            'company_email' => ['nullable','email','max:160'],
            'support_email' => ['nullable','email','max:160'],
            'phone_primary' => ['nullable','string','max:80'],
            'phone_secondary' => ['nullable','string','max:80'],
            'whatsapp_number' => ['nullable','string','max:80'],
            'facebook_url' => ['nullable','url','max:255'],
            'twitter_url' => ['nullable','url','max:255'],
            'linkedin_url' => ['nullable','url','max:255'],
        ]);

        foreach ($data as $key => $value) SiteSetting::setValue($key, $value, 'homepage');
        return back()->with('success','Site settings updated.');
    }
}
