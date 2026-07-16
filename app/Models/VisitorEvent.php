<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'visitor_hash',
        'path',
        'route_name',
        'referrer_host',
        'device_type',
        'user_agent_hash',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
