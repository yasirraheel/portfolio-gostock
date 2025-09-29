<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class UserEducation extends Model
{
    use HasFactory;

    protected $table = 'user_educations';

    protected $fillable = [
        'user_id',
        'institution_name',
        'degree',
        'field_of_study',
        'education_level',
        'start_date',
        'end_date',
        'is_current',
        'grade',
        'description',
        'activities',
        'location',
        'website',
        'logo',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_current' => 'boolean',
        'status' => 'string',
        'education_level' => 'string',
        'sort_order' => 'integer'
    ];

    /**
     * Get the user that owns the education
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get education level display name
     */
    public function getEducationLevelDisplayAttribute()
    {
        return match($this->education_level) {
            'high_school' => 'High School',
            'associate' => 'Associate Degree',
            'bachelor' => 'Bachelor\'s Degree',
            'master' => 'Master\'s Degree',
            'doctorate' => 'Doctorate',
            'diploma' => 'Diploma',
            'certificate' => 'Certificate',
            'professional' => 'Professional Certification',
            default => 'Degree'
        };
    }

    /**
     * Get duration of education
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
     * Get activities as array
     */
    public function getActivitiesArrayAttribute()
    {
        if (!$this->activities) {
            return [];
        }

        return array_map('trim', explode(',', $this->activities));
    }

    /**
     * Scope active educations
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope ordered educations
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('is_current', 'desc')
                    ->orderBy('start_date', 'desc')
                    ->orderBy('sort_order', 'asc');
    }

    /**
     * Get full degree title
     */
    public function getFullDegreeAttribute()
    {
        $parts = array_filter([
            $this->degree,
            $this->field_of_study ? 'in ' . $this->field_of_study : null
        ]);

        return implode(' ', $parts);
    }
}
