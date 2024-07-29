<?php

namespace Database\Seeders;

use App\Models\Admin\Permission;
use App\Models\Admin\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin permissions
        $admin_permissions = Permission::whereIn('title', [
            'admin_access',
            'agent_access',
            'role_index',
            'role_create',
            'role_store',
            'role_edit',
            'role_update',
            'role_delete',
            'permission_index',
            'permission_create',
            'permission_store',
            'permission_edit',
            'permission_update',
            'permission_delete',
            'agent_index',
            'agent_create',
            'agent_store',
            'agent_edit',
            'agent_show',
            'agent_delete',
            'agent_update',
            'agent_change_password_access',
            'transfer_log',
            'make_transfer',
            'game_type_access',
            'two_d_access',
            'three_d_access',
            'payment_type',
            'player_index',
        ]);
        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        // Admin permissions
        // Agent gets specific permissions
        $agent_permissions = Permission::whereIn('title', [
            'agent_access',
            'agent_index',
            'agent_create',
            'agent_store',
            'agent_edit',
            'agent_show',
            'agent_delete',
            'agent_update',
            'agent_change_password_access',
            'player_index',
            'player_create',
            'player_store',
            'player_edit',
            'player_show',
            'player_update',
            'player_delete',
            'transfer_log',
            'make_transfer',
            'withdraw_requests',
            'deposit_requests',
            'payment_type',
            'two_d_access',
            'two_d_agent_slip_access',
            'two_d_agent_all_slip_access',
            'morning_win',
            'evening_win',
            'two_d_all_win',
            'two_d_history',
            'three_d_access',
            'three_d_agent_slip',
            'three_d_agent_all_slip',
            'first_win',
            'second_win',
            'third_win',
            'three_d_all_win',
            'three_d_agent_histroy',
            'three_d_agent_all_histroy',
        ])->pluck('id');

        Role::findOrFail(2)->permissions()->sync($agent_permissions);
    }
}
