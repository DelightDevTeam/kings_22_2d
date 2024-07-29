<?php

namespace App\Models\ThreeD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreeDLimit extends Model
{
    use HasFactory;

    protected $table = 'three_d_limits';

    protected $fillable = [
        'three_d_limit',
    ];

    public function scopeLasted($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
