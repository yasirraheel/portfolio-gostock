<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPortfolioSlug implements ValidationRule
{
    /**
     * List of reserved slug names that cannot be used
     */
    protected $reservedSlugs = [
        'admin', 'administrator', 'user', 'users', 'api', 'login', 'register',
        'logout', 'account', 'settings', 'profile', 'dashboard', 'home', 'index',
        'about', 'contact', 'support', 'help', 'faq', 'terms', 'privacy', 'policy',
        'blog', 'news', 'media', 'images', 'css', 'js', 'assets', 'public',
        'storage', 'download', 'upload', 'files', 'cdn', 'www', 'mail', 'email',
        'search', 'browse', 'category', 'categories', 'tag', 'tags', 'popular',
        'trending', 'featured', 'latest', 'new', 'top', 'best', 'premium',
        'free', 'paid', 'subscription', 'pricing', 'plans', 'billing', 'payment',
        'invoice', 'checkout', 'cart', 'wishlist', 'favorites', 'bookmarks',
        'notifications', 'messages', 'inbox', 'sent', 'drafts', 'trash',
        'feed', 'timeline', 'activity', 'history', 'stats', 'analytics',
        'reports', 'export', 'import', 'backup', 'restore', 'maintenance',
        'error', '404', '500', 'test', 'demo', 'example', 'sample',
        'root', 'system', 'config', 'setup', 'install', 'update', 'upgrade',
        'null', 'undefined', 'true', 'false', 'yes', 'no', 'on', 'off'
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Let required validation handle empty values
        }

        // Check if slug contains only allowed characters (letters, numbers, hyphens, underscores)
        if (!preg_match('/^[a-zA-Z0-9_-]+$/', $value)) {
            $fail('The :attribute can only contain letters, numbers, hyphens, and underscores.');
            return;
        }

        // Check if slug starts and ends with alphanumeric character
        if (!preg_match('/^[a-zA-Z0-9].*[a-zA-Z0-9]$/', $value) && strlen($value) > 1) {
            $fail('The :attribute must start and end with a letter or number.');
            return;
        }

        // Check minimum length
        if (strlen($value) < 3) {
            $fail('The :attribute must be at least 3 characters long.');
            return;
        }

        // Check maximum length
        if (strlen($value) > 100) {
            $fail('The :attribute may not be greater than 100 characters.');
            return;
        }

        // Check if slug is in reserved list (case-insensitive)
        if (in_array(strtolower($value), $this->reservedSlugs)) {
            $fail('The :attribute "' . $value . '" is reserved and cannot be used.');
            return;
        }

        // Check for consecutive hyphens or underscores
        if (preg_match('/[-_]{2,}/', $value)) {
            $fail('The :attribute cannot contain consecutive hyphens or underscores.');
            return;
        }
    }
}
