<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserPaymentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = [
            [
                'account_name' => 'Testing KPay Account',
                'account_no' => '1278767876767',
                'user_id' => 2,
                'payment_type_id' => 1,
            ],
            [
                'account_name' => 'Testing Wave Account',
                'account_no' => '1298789986678656',
                'user_id' => 2,
                'payment_type_id' => 2,
            ],
        ];

        DB::table('user_payments')->insert($banks);

    }
}
