<?php

namespace App\Models\ThreeD;

use App\Models\ThreeD\LotteryThreeDigitPivot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lotto extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_amount',
        'user_id',
        'slip_no',

    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lotteryThreeDigitPivots()
    {
        return $this->hasMany(LotteryThreeDigitPivot::class, 'lotto_id');
    }
}
