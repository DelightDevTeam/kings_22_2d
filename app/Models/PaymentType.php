<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image'];

    protected $appends = ['img_url'];

    public function getImgUrlAttribute()
    {
        return asset('assets/img/paymentType/'.$this->image);
    }

    public function paymentImages()
    {
        return $this->hasMany(PaymentImage::class, 'payment_type_id');
    }
}
