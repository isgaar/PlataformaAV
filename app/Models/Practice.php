<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Practice extends Model
{
    protected $fillable = [
        'name',
        'description',
        'source_practice',
        'source_reference_image',
    ];
}
