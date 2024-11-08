<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserInfoStatus extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'order_id',
        'is_complete_info',
        'percentage',
    ];


    public function userSize()
    {
        return $this->hasOne(UserSize::class);
    }
    public function userQuestion()
    {
        return $this->hasOne(userQuestions::class);
    }
    public function userImage()
    {
        return $this->hasOne(UserImage::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function programs()
    {
        return $this->hasOne(Program::class);
    }
}
