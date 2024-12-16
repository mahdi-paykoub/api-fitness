<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'orderable_id',
        'orderable_type',
        'status',
        'price',
        'tracking_serial',
        'turn_code',
        'visit',
        'subscribe_code',
    ];

    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userInfoStatus()
    {
        return $this->hasOne(UserInfoStatus::class);
    }
}
