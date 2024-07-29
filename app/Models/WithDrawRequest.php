<?php

namespace App\Models;

use App\Models\Admin\Bank;
use App\Models\Admin\UserPayment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithDrawRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'agent_id', 'user_payment_id', 'amount', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userPayment()
    {
        return $this->belongsTo(UserPayment::class);
    }
}
