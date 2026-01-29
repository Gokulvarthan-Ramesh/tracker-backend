<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyLog extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'subject',
        'hours',
        'notes',
        'description', 
    ];
}
