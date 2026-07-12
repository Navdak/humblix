<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name','country','state_city','address','phone','email','manager_name','service_coverage','status','sort_order','is_published'];

    protected function casts(): array
    {
        return ['is_published' => 'boolean'];
    }
}
