<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribeCode extends Model
{
    use HasFactory;
    protected $fillable = [
        'code',
        'type',
        'value',
        'active',
        'user_id',
        'for',
        'score',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
