<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeamMember extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','role','region','experience','certifications','bio','email','phone','social_url','photo_path','is_visible','sort_order'];
    protected function casts(): array { return ['is_visible' => 'boolean', 'sort_order' => 'integer']; }
}
