<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dhikr extends Model
{

    protected $table = 'adhkar';

    protected $fillable = [
        'category',
        'text',
        'translation',
        'audio',
        'repeat'
    ];
}

