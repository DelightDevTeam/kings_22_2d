<?php

namespace App\Models\Admin;

use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    use HasFactory;

    protected $fillable = ['account_name', 'account_no', 'payment_type_id', 'user_id'];

    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}
