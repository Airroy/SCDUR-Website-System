<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $table = 'scd_directives';

    protected $fillable = [
        'scd_year_id',
        'parent_id',
        'type',
        'name',
        'sequence',
        'image_path',
        'file_path',
        'view_count',
        'download_count',
        'is_hidden',
    ];

    protected $casts = [
        'sequence' => 'integer',
        'view_count' => 'integer',
        'download_count' => 'integer',
        'is_hidden' => 'boolean',
    ];

    // Relationships
    public function scdYear(): BelongsTo
    {
        return $this->belongsTo(ScdYear::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Order::class, 'parent_id')->orderBy('sequence');
    }

    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    // Scopes
    public function scopeRootNodes($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeFolders($query)
    {
        return $query->where('type', 'folder');
    }

    public function scopeFiles($query)
    {
        return $query->where('type', 'file');
    }

    // Helper methods
    public function isFolder(): bool
    {
        return $this->type === 'folder';
    }

    public function isFile(): bool
    {
        return $this->type === 'file';
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }
}
