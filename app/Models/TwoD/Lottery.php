<?php

namespace App\Models\TwoD;

use App\Models\TwoD\LotteryTwoDigitPivot;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    use HasFactory;

    protected $fillable = [
        'pay_amount',
        'total_amount',
        'user_id',
        'slip_no',
        'session',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function twoDigits()
    {
        return $this->belongsToMany(TwoDigit::class, 'lottery_two_digit_pivot')->withPivot('sub_amount', 'prize_sent')->withTimestamps();
    }

    public function lotteryTwoDigitPivots()
    {
        return $this->hasMany(LotteryTwoDigitPivot::class);
    }
}
