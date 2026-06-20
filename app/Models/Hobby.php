<?php
// app/Models/Hobby.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hobby extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'name', 'level', 'frequency', 'status', 
        'emoji', 'progress', 'description'
    ];

    protected $casts = [
        'progress' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function hobbies()
    {
        return $this->hasMany(Hobby::class);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}