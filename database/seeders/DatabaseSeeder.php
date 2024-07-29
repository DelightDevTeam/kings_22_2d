<?php

namespace Database\Seeders;

use App\Models\Admin\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            PaymentTypeTableSeeder::class,
            UserPaymentTableSeeder::class,
            GameTypeTableSeeder::class,
            ProductTableSeeder::class,
            GameTypeProductTableSeeder::class,
            ProductTableSeeder::class,
            BannerSeeder::class,
            BannerTextSeeder::class,
            TwoDigitsTableSeeder::class,
            TwoDLimitTableSeeder::class,
            TwodSettingTableSeeder::class,
            ThreeDDigitTablesSeeder::class,
            ThreeDLimitTablesSeeder::class,
            ThreedSettingTableSeeder::class,
            ThreedMatchTimeSeeder::class,
            ChatSeeder::class,
        ]);
    }
}
