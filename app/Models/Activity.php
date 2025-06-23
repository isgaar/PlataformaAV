<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    // Solo llenables los campos existentes. Elimina "description" si no existe en la tabla.
    protected $fillable = ['name'];

    /**
     * Relación muchos a muchos con usuarios.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('done', 'session')
            ->withTimestamps();
    }
}