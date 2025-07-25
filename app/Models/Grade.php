<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}