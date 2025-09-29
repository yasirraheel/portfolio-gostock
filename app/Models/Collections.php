<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collections extends Model
{
    // This is a stub model to prevent class loading errors
    // The actual Collections functionality has been removed from this starter kit
    protected $fillable = [];

    public static function first()
    {
        // Return a basic object to satisfy dependency injection
        return new self();
    }

    public static function where($column, $value = null, $operator = null)
    {
        // Return a query builder that will fail with a descriptive message
        abort(404, 'Collections functionality has been removed from this starter kit');
    }
}
