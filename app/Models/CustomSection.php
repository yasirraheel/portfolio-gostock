<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'icon',
        'order_position',
        'image',
        'link_url',
        'link_text',
        'status',
    ];

    protected $casts = [
        'order_position' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Get the user that owns the custom section.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
