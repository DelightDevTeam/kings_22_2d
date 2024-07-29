<?php

namespace App\Models\TwoD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Internet extends Model
{
    use HasFactory;

    protected $fillable = [
        'internet_digit',
        'session',
        'open_time',
    ];
}
