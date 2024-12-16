<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requestsharecode extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'has_code',
        'title',

    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
