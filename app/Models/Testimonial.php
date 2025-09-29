<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_name',
        'client_position',
        'company_name',
        'client_website',
        'client_photo',
        'testimonial_text',
        'rating',
        'date_received',
        'project_type',
        'project_details',
        'is_featured',
        'status'
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

    protected $casts = [
        'date_received' => 'date',
        'is_featured' => 'boolean',
        'rating' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
