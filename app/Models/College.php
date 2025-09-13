<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
        'slug',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Allow resolving by slug, short slug like "cedat", or acronym in parentheses
    public function resolveRouteBinding($value, $field = null)
    {
        // Try exact slug provided
        $college = static::where('slug', $value)->first();
        if ($college) return $college;

        // Try slugified version of the provided value
        $slug = Str::slug($value);
        if ($slug !== $value) {
            $college = static::where('slug', $slug)->first();
            if ($college) return $college;
        }

        // Try matching acronym in parentheses within the name, e.g. (CEDAT)
        $valLower = strtolower($value);
        $college = static::whereRaw('LOWER(name) LIKE ?', ['%(' . $valLower . ')%'])->first();
        if ($college) return $college;

        return null;
    }
}
