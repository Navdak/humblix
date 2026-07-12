@csrf
@if($item->exists) @method('PUT') @endif
<div class="form-grid">
    <div class="form-field"><label>Name</label><input name="name" value="{{ old('name',$item->name) }}" required></div>
    <div class="form-field"><label>Category</label><select name="category" required><option value="">Select category</option>@foreach(\App\Models\EquipmentItem::CATEGORIES as $category)<option value="{{ $category }}" @selected(old('category',$item->category) === $category)>{{ $category }}</option>@endforeach</select></div>
    <div class="form-field"><label>Availability</label><select name="availability_status" required>@foreach(\App\Models\EquipmentItem::AVAILABILITY as $status)<option value="{{ $status }}" @selected(old('availability_status',$item->availability_status ?: 'available_on_request') === $status)>{{ ucwords(str_replace('_',' ', $status)) }}</option>@endforeach</select></div>
    <div class="form-field"><label>Image</label><input type="file" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"></div>
    <div class="form-field full"><label>Short Description</label><textarea name="short_description" rows="3" maxlength="400">{{ old('short_description',$item->short_description) }}</textarea></div>
    <div class="form-field full"><label>Specification</label><textarea name="specification" rows="6">{{ old('specification',$item->specification) }}</textarea></div>
    <div class="form-field"><label>Sort Order</label><input type="number" min="0" name="sort_order" value="{{ old('sort_order',$item->sort_order ?? 0) }}"></div>
    <label style="display:flex;align-items:center;gap:8px;margin-top:26px"><input type="checkbox" name="is_published" value="1" @checked(old('is_published',$item->is_published ?? true)) style="width:auto"> Publish item</label>
</div>
@if($item->image_path)<p class="field-help" style="margin-top:14px">Current image: {{ $item->image_path }}</p>@endif
<button class="btn btn-primary" style="margin-top:20px">Save Equipment Item</button>
