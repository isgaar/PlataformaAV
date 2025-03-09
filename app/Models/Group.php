<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'grade_id'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}