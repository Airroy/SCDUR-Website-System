<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScdReport extends Model
{
    protected $fillable = [
        'scd_year_id',
        'file_name',
        'file_path',
    ];

    public function scdYear(): BelongsTo
    {
        return $this->belongsTo(ScdYear::class);
    }
}
