<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key','value','type','group'];

    public static function setValue(string $key, ?string $value, string $group = 'general', string $type = 'text'): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group, 'type' => $type]);
        Cache::forget('site_settings_public');
    }
}
