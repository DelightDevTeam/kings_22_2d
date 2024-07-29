<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentImage extends Model
{
    use HasFactory;

    protected $fillable = ['payment_type_id', 'image'];

    protected $appends = ['img_url'];

    public function getImgUrlAttribute()
    {
        return asset('assets/img/paymentType/banners/'.$this->image);
    }
}
