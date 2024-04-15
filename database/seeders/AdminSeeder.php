<?php

namespace Database\Seeders;

use App\Http\Controllers\RoleController;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'hamza',
            'email' => 'hamza@gmail.com',
            'password' => Hash::make('hamza@gmail.com')
        ]);

        RoleController::giveRoleToUser_static($admin->id, 'admin');
    }
}
