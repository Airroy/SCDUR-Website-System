<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ScdYear extends Model
{
    protected $fillable = [
        'year',
        'created_date',
        'is_published',
    ];

    protected $casts = [
        'created_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function report(): HasOne
    {
        return $this->hasOne(ScdReport::class);
    }

    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function contentSections(): HasMany
    {
        return $this->hasMany(ContentSection::class);
    }

    public function getYearNameAttribute(): string
    {
        return 'SCD ' . $this->year;
    }
}
