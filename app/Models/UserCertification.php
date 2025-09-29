<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class UserCertification extends Model
{
    use HasFactory;

    protected $table = 'user_certifications';

    protected $fillable = [
        'user_id',
        'name',
        'issuing_organization',
        'issue_date',
        'expiry_date',
        'does_not_expire',
        'credential_id',
        'credential_url',
        'description',
        'skills_gained',
        'certificate_image',
        'organization_logo',
        'status',
        'sort_order'
    ];

    protected $casts = [
        'issue_date' => 'datetime',
        'expiry_date' => 'datetime',
        'does_not_expire' => 'boolean',
        'status' => 'string',
        'sort_order' => 'integer'
    ];

    /**
     * Get the user that owns the certification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if certification is expired
     */
    public function getIsExpiredAttribute()
    {
        if ($this->does_not_expire || !$this->expiry_date) {
            return false;
        }

        return $this->expiry_date->isPast();
    }

    /**
     * Get expiry status display
     */
    public function getExpiryStatusAttribute()
    {
        if ($this->does_not_expire) {
            return 'Never expires';
        }

        if (!$this->expiry_date) {
            return 'No expiry date';
        }

        if ($this->is_expired) {
            return 'Expired';
        }

        // Check if expiring within 30 days
        if ($this->expiry_date->diffInDays(now()) <= 30) {
            return 'Expiring soon';
        }

        return 'Active';
    }

    /**
     * Get validity period
     */
    public function getValidityPeriodAttribute()
    {
        $issued = $this->issue_date->format('M Y');

        if ($this->does_not_expire) {
            return $issued . ' - Never expires';
        }

        if (!$this->expiry_date) {
            return $issued . ' - No expiry';
        }

        $expires = $this->expiry_date->format('M Y');
        return $issued . ' - ' . $expires;
    }

    /**
     * Get skills as array
     */
    public function getSkillsArrayAttribute()
    {
        if (!$this->skills_gained) {
            return [];
        }

        return array_map('trim', explode(',', $this->skills_gained));
    }

    /**
     * Scope active certifications
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope valid certifications (not expired)
     */
    public function scopeValid($query)
    {
        return $query->where(function($q) {
            $q->where('does_not_expire', true)
              ->orWhereNull('expiry_date')
              ->orWhere('expiry_date', '>=', now());
        });
    }

    /**
     * Scope ordered certifications
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('issue_date', 'desc')
                    ->orderBy('sort_order', 'asc');
    }

    /**
     * Get days until expiry
     */
    public function getDaysUntilExpiryAttribute()
    {
        if ($this->does_not_expire || !$this->expiry_date) {
            return null;
        }

        return max(0, $this->expiry_date->diffInDays(now()));
    }
}
