<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContentNode extends Model
{
    protected $fillable = [
        'scd_year_id',
        'parent_id',
        'category_group',
        'type',
        'name',
        'sequence',
        'image_path',
        'file_path',
    ];

    protected $casts = [
        'sequence' => 'integer',
    ];

    // Relationships
    public function scdYear(): BelongsTo
    {
        return $this->belongsTo(ScdYear::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ContentNode::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ContentNode::class, 'parent_id')->orderBy('sequence');
    }

    // Recursive children
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    // Scopes
    public function scopeOfGroup($query, $group)
    {
        return $query->where('category_group', $group);
    }

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