<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'front',
        'back',
        'side',
        'user_info_status_id',
    ];
}
