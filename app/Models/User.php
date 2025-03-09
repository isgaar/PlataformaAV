<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'school_id',
        'grade_id',
        'group_id',
        'turno_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación con la escuela
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relación con el grado
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    // Relación con el grupo
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    // Relación con el turno
    // En el modelo User
    public function turno()
    {
        return $this->belongsTo(Turno::class); // Relación con el modelo Turno
    }


    // Método para obtener un solo rol (más limpio para la vista)
    public function getRole(): string
    {
        return $this->getRoleNames()->first() ?? 'Sin rol';
    }
}
