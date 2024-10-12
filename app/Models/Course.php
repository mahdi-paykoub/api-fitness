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
        'image',
        'body',
        'slug',
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
