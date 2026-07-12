<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentItem extends Model
{
    public const CATEGORIES = ['HVAC Equipment','Solar Panels','Inverters & Batteries','Electrical Components','Mounting Kits','Spare Parts','Tools & Accessories','Home Installation Products'];
    public const AVAILABILITY = ['available_on_request','in_stock','limited','unavailable'];

    protected $fillable = ['name','category','short_description','specification','availability_status','image_path','sort_order','is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }
}
