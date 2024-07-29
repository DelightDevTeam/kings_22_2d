<?php

namespace Database\Seeders;

use App\Enums\TransactionName;
use App\Enums\UserType;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = $this->createUser(UserType::Admin, 'Owner', 'king2d', '09123456789');
        (new WalletService)->deposit($admin, 10 * 100_000, TransactionName::CapitalDeposit);

        $agent_1 = $this->createUser(UserType::Agent, 'SuperAgent', 'A898737', '09112345674', $admin->id);
        $this->createUser(UserType::Player, 'Player 1', 'SB111111', '09111111111', $agent_1->id);
    }

    private function createUser(UserType $type, $name, $user_name, $phone, $parent_id = null)
    {
        return User::create([
            'name' => $name,
            'user_name' => $user_name,
            'phone' => $phone,
            'password' => Hash::make('amkfreelancer'),
            'agent_id' => $parent_id,
            'status' => 1,
            'main_balance' => 50000,
            'type' => $type->value,
        ]);
    }
}
