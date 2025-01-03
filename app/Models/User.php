<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'phone',
        'name',
        'admin',
        'status',
    ];

    public function customTokens()
    {
        return $this->hasMany(CustomToken::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function userInfoStatus()
    {
        return $this->hasMany(UserInfoStatus::class);
    }

    public function personalInfo()
    {
        return $this->hasOne(PersonalInfo::class);
    }
    public function requestsharecode()
    {
        return $this->hasOne(Requestsharecode::class);
    }
    public function subscribeCode()
    {
        return $this->hasOne(SubscribeCode::class);
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }
    public function settlements()
    {
        return $this->hasMany(Settlement::class);
    }
    public function offs()
    {
        return $this->belongsToMany(Off::class);
    }
}
