<?php

namespace App\Models\ThreeD;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LottoSlipNumberCounter extends Model
{
    use HasFactory;

    protected $fillable = ['current_number'];
}
