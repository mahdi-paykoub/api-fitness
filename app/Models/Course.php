<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected $fillable = [
        'title',
        'price',
        'off_price',
        'image',
        'body',
        'slug',
    ];
    public function orders()
    {
        return $this->morphMany(Order::class, 'orderable');
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
