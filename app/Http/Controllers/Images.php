<?php

namespace App\Http\Controllers;

use App\Models\Images as ImagesModel;

class Images
{
    public static function first()
    {
        // Delegate to the model
        return ImagesModel::first();
    }

    public function __call($method, $parameters)
    {
        abort(404, 'Stock photo functionality has been removed from this starter kit');
    }

    public static function __callStatic($method, $parameters)
    {
        abort(404, 'Stock photo functionality has been removed from this starter kit');
    }
}
