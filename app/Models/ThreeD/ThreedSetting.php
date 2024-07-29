<?php

namespace App\Models\ThreeD;

use App\Jobs\CheckForThreeDWinners;
use App\Jobs\CheckForThreeDWinnersWithPermutations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreedSetting extends Model
{
    use HasFactory;

    protected $fillable = ['result_date', 'result_time', 'result_number', 'status', 'match_start_date', 'prize_status', 'closed_time'];

    protected static function booted()
    {
        static::updated(function ($three_d_winner) {
            CheckForThreeDWinners::dispatch($three_d_winner);
            CheckForThreeDWinnersWithPermutations::dispatch($three_d_winner);
        });
    }
}
