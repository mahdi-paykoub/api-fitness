<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSize extends Model
{
    use HasFactory;
    protected $fillable = [
        'height',
        'weight',
        'neck',
        'shoulder',
        'arm',
        'contracted_arm',
        'forearm',
        'wrist',
        'chest',
        'belly',
        'waist',
        'hips',
        'thigh',
        'leg',
        'ankle',
        'user_info_status_id',
    ];

    public function userInfoStatus()
    {
        return $this->belongsTo(UserInfoStatus::class);
    }
}
