<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Configuration extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;
    public static function getValue($key, $default = null)
    {
        return Cache::remember("config:$key", 3600, function () use ($key, $default) {
            $config = static::where('key', $key)->first();

            if (!$config) {
                return $default;
            }

            return $config->value;
        });
    }
}