<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_name',
        'description',
        'project_type',
        'status',
        'start_date',
        'end_date',
        'project_url',
        'github_url',
        'demo_url',
        'technologies',
        'project_images',
        'client_name',
        'role',
        'team_size',
        'key_features',
        'challenges_solved',
        'visibility',
        'featured'
    ];

    protected $casts = [
        'technologies' => 'array',
        'project_images' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'featured' => 'boolean'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getDurationAttribute()
    {
        if (!$this->start_date) {
            return null;
        }

        $start = Carbon::parse($this->start_date);
        $end = $this->end_date ? Carbon::parse($this->end_date) : Carbon::now();

        // Use diffInMonths to get total months, then calculate years and remaining months
        $totalMonths = $start->diffInMonths($end);
        $years = intval($totalMonths / 12);
        $months = $totalMonths % 12;
        
        // If we have years and months, we don't need days
        // Only show days if we have less than a month
        $days = 0;
        if ($totalMonths == 0) {
            $days = $start->diffInDays($end);
        }

        $duration = [];
        if ($years > 0) {
            $duration[] = $years . ' ' . ($years == 1 ? 'year' : 'years');
        }
        if ($months > 0) {
            $duration[] = $months . ' ' . ($months == 1 ? 'month' : 'months');
        }
        if (empty($duration) && $days > 0) {
            $duration[] = $days . ' ' . ($days == 1 ? 'day' : 'days');
        }

        return empty($duration) ? '1 day' : implode(', ', $duration);
    }

    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'planning' => 'Planning',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'on_hold' => 'On Hold',
            'cancelled' => 'Cancelled'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'planning' => 'secondary',
            'in_progress' => 'primary',
            'completed' => 'success',
            'on_hold' => 'warning',
            'cancelled' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getProjectTypeDisplayAttribute()
    {
        $types = [
            'personal' => 'Personal',
            'professional' => 'Professional',
            'open_source' => 'Open Source',
            'freelance' => 'Freelance',
            'startup' => 'Startup',
            'academic' => 'Academic',
            'other' => 'Other'
        ];

        return $types[$this->project_type] ?? $this->project_type;
    }

    public function getTechnologiesListAttribute()
    {
        return is_array($this->technologies) ? $this->technologies : [];
    }

    public function getProjectImagesListAttribute()
    {
        return is_array($this->project_images) ? $this->project_images : [];
    }

    public function getFormattedStartDateAttribute()
    {
        return $this->start_date ? Carbon::parse($this->start_date)->format('M Y') : null;
    }

    public function getFormattedEndDateAttribute()
    {
        return $this->end_date ? Carbon::parse($this->end_date)->format('M Y') : 'Present';
    }

    public function getIsOngoingAttribute()
    {
        return !$this->end_date || $this->status === 'in_progress';
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('project_type', $type);
    }

    // Helper methods
    public function getTechnologyCount()
    {
        return count($this->technologies_list);
    }

    public function getImageCount()
    {
        return count($this->project_images_list);
    }

    public function hasImages()
    {
        return !empty($this->project_images_list);
    }

    public function getMainImage()
    {
        return !empty($this->project_images_list) ? $this->project_images_list[0] : null;
    }
}
