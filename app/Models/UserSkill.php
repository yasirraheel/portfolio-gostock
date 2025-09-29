<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_name',
        'description',
        'fas_icon',
        'status',
        'proficiency_level'
    ];

    protected $casts = [
        'status' => 'string',
        'proficiency_level' => 'string',
    ];

    /**
     * Get the user that owns the skill
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get proficiency level display name
     */
    public function getProficiencyDisplayAttribute()
    {
        return match($this->proficiency_level) {
            'beginner' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced',
            'expert' => 'Expert',
            default => 'Intermediate'
        };
    }

    /**
     * Get proficiency level percentage for display
     */
    public function getProficiencyPercentageAttribute()
    {
        return match($this->proficiency_level) {
            'beginner' => 25,
            'intermediate' => 50,
            'advanced' => 75,
            'expert' => 100,
            default => 50
        };
    }

    /**
     * Scope active skills
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
