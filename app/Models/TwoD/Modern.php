<?php

namespace App\Models\TwoD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modern extends Model
{
    use HasFactory;

    protected $fillable = [
        'modern_digit',
        'session',
        'open_time',
    ];
}
