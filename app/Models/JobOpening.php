<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobOpening extends Model
{
    protected $fillable = ['title','department','location','employment_type','description','requirements','status','published_at','closing_date','application_email','application_url','sort_order'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime', 'closing_date' => 'date'];
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'open')->whereNotNull('published_at');
    }
}
