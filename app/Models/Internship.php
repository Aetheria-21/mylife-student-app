<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internship extends Model
{
    use HasFactory;

    protected $fillable = ['company','position','date_applied', 'response_date', 'status', 'user_id'];

    protected $casts = [
        'date_applied' => 'date',
        'response_date' => 'date',
    ];
}
