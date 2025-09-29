<?php

namespace App\Http\Controllers;

use App\Models\Collections as CollectionsModel;

class Collections
{
    public static function first()
    {
        // Delegate to the model
        return CollectionsModel::first();
    }

    public function __call($method, $parameters)
    {
        abort(404, 'Collections functionality has been removed from this starter kit');
    }

    public static function __callStatic($method, $parameters)
    {
        abort(404, 'Collections functionality has been removed from this starter kit');
    }
}
