<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class UserExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'job_title',
        'employment_type',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'achievements',
        'technologies_used',
        'company_website',
        'company_logo',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_current' => 'boolean',
        'status' => 'string',
        'employment_type' => 'string',
        'sort_order' => 'integer'
    ];

    /**
     * Get the user that owns the experience
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get employment type display name
     */
    public function getEmploymentTypeDisplayAttribute()
    {
        return match($this->employment_type) {
            'full_time' => 'Full-time',
            'part_time' => 'Part-time',
            'contract' => 'Contract',
            'freelance' => 'Freelance',
            'internship' => 'Internship',
            'temporary' => 'Temporary',
            default => 'Full-time'
        };
    }

    /**
     * Get duration of experience
     */
    public function getDurationAttribute()
    {
        $start = $this->start_date;
        $end = $this->is_current ? now() : $this->end_date;

        if (!$end) {
            return 'Unknown duration';
        }

        $diff = $start->diff($end);
        $years = $diff->y;
        $months = $diff->m;

        $duration = '';
        if ($years > 0) {
            $duration .= $years . ' year' . ($years > 1 ? 's' : '');
        }
        if ($months > 0) {
            if ($years > 0) $duration .= ' ';
            $duration .= $months . ' month' . ($months > 1 ? 's' : '');
        }

        return $duration ?: '1 month';
    }

    /**
     * Get formatted date range
     */
    public function getDateRangeAttribute()
    {
        $start = $this->start_date->format('M Y');
        $end = $this->is_current ? 'Present' : ($this->end_date ? $this->end_date->format('M Y') : 'Present');

        return $start . ' - ' . $end;
    }

    /**
     * Get technologies as array
     */
    public function getTechnologiesArrayAttribute()
    {
        if (!$this->technologies_used) {
            return [];
        }

        return array_map('trim', explode(',', $this->technologies_used));
    }

    /**
     * Scope active experiences
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope ordered experiences
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('is_current', 'desc')
                    ->orderBy('start_date', 'desc')
                    ->orderBy('sort_order', 'asc');
    }
}
