<?php

namespace Database\Seeders;

use App\Models\PaymentImage;
use App\Models\PaymentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Kpay Account',
                'image' => 'kpay.png',
            ],
            [
                'name' => 'Wave Account',
                'image' => 'wave.png',
            ],
        ];

        DB::table('payment_types')->insert($types);

        $types = PaymentType::all();

        foreach ($types as $type) {
            PaymentImage::create([
                'payment_type_id' => $type->id,
                'image' => 'kpay.png',
            ]);
        }
    }
}
