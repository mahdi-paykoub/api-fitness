<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Off extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'usage',
        'max_usage',
        'for',
        'type',
        'value',
        'all_user',
    ];

   
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function offable()  
    {  
        return $this->morphTo();  
    }  
   
}
