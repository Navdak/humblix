<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SeoSettingsController extends Controller
{
    public function index()
    {
        $settings = SeoSetting::orderBy('page_label')->get();

        return view('admin.seo-settings.index', compact('settings'));
    }

    public function edit(SeoSetting $seoSetting)
    {
        return view('admin.seo-settings.edit', ['setting' => $seoSetting]);
    }

    public function update(Request $request, SeoSetting $seoSetting): RedirectResponse
    {
        $data = $request->validate([
            'page_label' => ['required', 'string', 'max:120'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:170'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:70'],
            'og_description' => ['nullable', 'string', 'max:200'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'twitter_title' => ['nullable', 'string', 'max:70'],
            'twitter_description' => ['nullable', 'string', 'max:200'],
            'twitter_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'noindex' => ['nullable', 'boolean'],
            'nofollow' => ['nullable', 'boolean'],
            'structured_data_json' => ['nullable', 'string', function (string $attribute, mixed $value, \Closure $fail): void {
                if (blank($value)) {
                    return;
                }

                json_decode((string) $value, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $fail('Structured data must be valid JSON.');
                }
            }],
            'page_key' => ['prohibited'],
        ]);

        if ($request->hasFile('og_image')) {
            if ($seoSetting->og_image) {
                Storage::disk('public')->delete($seoSetting->og_image);
            }
            $data['og_image'] = $request->file('og_image')->store('seo', 'public');
        }

        if ($request->hasFile('twitter_image')) {
            if ($seoSetting->twitter_image) {
                Storage::disk('public')->delete($seoSetting->twitter_image);
            }
            $data['twitter_image'] = $request->file('twitter_image')->store('seo', 'public');
        }

        $data['noindex'] = (bool) $request->boolean('noindex');
        $data['nofollow'] = (bool) $request->boolean('nofollow');
        $data['updated_by'] = auth()->id();

        if (filled($data['structured_data_json'] ?? null)) {
            $data['structured_data_json'] = json_decode((string) $data['structured_data_json'], true);
        } else {
            $data['structured_data_json'] = null;
        }

        $seoSetting->update($data);
        Cache::forget("seo_setting_{$seoSetting->page_key}");

        return redirect()->route('admin.seo-settings.index')->with('success', 'SEO settings updated.');
    }
}
