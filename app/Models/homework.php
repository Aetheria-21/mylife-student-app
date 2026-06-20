<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    protected $table = 'homeworks';

    protected $fillable = ['lesson_id', 'user_id', 'title', 'is_done', 'due_date', 'remind_at'];



    protected $casts = [
        'is_done'   => 'boolean',
        'due_date'  => 'date',
        'remind_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
