<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    protected $fillable = [
        'title',
        'slug',
        'price',
        'off_price',
        'description',
        'body',
        'visit',
        'visit_price',
        'duration',
        'features',
    ];

    public function orders()
    {
        return $this->morphMany(Order::class, 'orderable');
    }
}
