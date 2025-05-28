<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_year',
        'end_year',
    ];

    // Relación con usuarios (alumnos que tienen este periodo)
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Opcional: acceso rápido al rango como texto
    public function getRangeAttribute()
    {
        return "{$this->start_year} - {$this->end_year}";
    }
}
