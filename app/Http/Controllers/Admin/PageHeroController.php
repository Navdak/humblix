<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageHero;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageHeroController extends Controller
{
    public function index()
    {
        return view('admin.page-heroes.index', [
            'heroes' => PageHero::query()->orderBy('sort_order')->orderBy('label')->get(),
        ]);
    }

    public function edit(PageHero $pageHero)
    {
        return view('admin.page-heroes.edit', ['hero' => $pageHero]);
    }

    public function update(Request $request, PageHero $pageHero): RedirectResponse
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:120'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:700'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->boolean('remove_image')) {
            $this->deleteUploadedImage($pageHero);
            $data['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            $this->deleteUploadedImage($pageHero);
            $data['image_path'] = $request->file('image')->store('page-heroes', 'public');
        }

        unset($data['image']);
        $pageHero->update($data);

        return redirect()->route('admin.page-heroes.edit', $pageHero)->with('success', 'Page hero updated.');
    }

    private function deleteUploadedImage(PageHero $pageHero): void
    {
        if ($pageHero->hasUploadedImage()) {
            Storage::disk('public')->delete($pageHero->image_path);
        }
    }
}
