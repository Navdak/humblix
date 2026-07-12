<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = ['title','slug','client_type','country','location','sector','service_division','system_type','challenge','solution','result','equipment_used','safety_controls','duration','outcome','client_testimonial','image_path','gallery','is_featured','status'];

    protected function casts(): array
    {
        return ['gallery' => 'array', 'is_featured' => 'boolean'];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function booted(): void
    {
        static::saving(function (Project $project): void {
            if (! $project->slug) {
                $project->slug = Str::slug($project->title);
            }
        });
    }
}
