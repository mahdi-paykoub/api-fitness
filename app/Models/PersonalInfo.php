<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'birth_date',
        'state',
        'city',
        'gender',
        'how_know',
        'how_know_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
