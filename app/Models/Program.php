<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file',
        'user_info_status_id',
    ];

    public function userInfoStatus()
    {
        return $this->belongsTo(UserInfoStatus::class);
    }
}
