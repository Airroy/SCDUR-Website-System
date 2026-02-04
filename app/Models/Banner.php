<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    protected $fillable = [
        'scd_year_id',
        'sequence',
        'title',
        'image_path',
        'link_type',
        'link_url',
        'pdf_name',
        'pdf_path',
    ];

    protected $casts = [
        'sequence' => 'integer',
    ];

    public function scdYear(): BelongsTo
    {
        return $this->belongsTo(ScdYear::class);
    }
}
