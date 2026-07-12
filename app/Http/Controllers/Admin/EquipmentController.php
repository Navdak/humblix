<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EquipmentItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index() { return view('admin.equipment.index', ['items' => EquipmentItem::orderBy('sort_order')->latest()->paginate(15)]); }
    public function create() { return view('admin.equipment.create', ['item' => new EquipmentItem()]); }
    public function store(Request $request): RedirectResponse { EquipmentItem::create($this->validated($request)); return redirect()->route('admin.equipment.index')->with('success','Equipment item created.'); }
    public function edit(EquipmentItem $equipment) { return view('admin.equipment.edit', ['item' => $equipment]); }
    public function update(Request $request, EquipmentItem $equipment): RedirectResponse { $equipment->update($this->validated($request)); return back()->with('success','Equipment item updated.'); }
    public function destroy(EquipmentItem $equipment): RedirectResponse { $equipment->delete(); return redirect()->route('admin.equipment.index')->with('success','Equipment item deleted.'); }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'=>['required','string','max:180'], 'category'=>['required','in:'.implode(',', EquipmentItem::CATEGORIES)],
            'short_description'=>['nullable','string','max:400'], 'specification'=>['nullable','string','max:5000'],
            'availability_status'=>['required','in:'.implode(',', EquipmentItem::AVAILABILITY)],
            'image'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],
            'sort_order'=>['nullable','integer','min:0'], 'is_published'=>['nullable','boolean'],
        ]);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['is_published'] = (bool) $request->boolean('is_published');
        if ($request->hasFile('image')) $data['image_path'] = $request->file('image')->store('equipment','public');
        unset($data['image']);
        return $data;
    }
}
