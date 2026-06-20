<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';

    protected $fillable = ['user_id', 'title', 'description', 'date'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function homeworks()
    {
        return $this->hasMany(Homework::class);
    }
}

