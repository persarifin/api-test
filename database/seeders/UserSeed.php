<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::updateOrCreate([
            'name' => 'Arifin',
            'email'=> 'arifin@gmail.com',
        ],[
            'name' => 'Arifin',
            'email'=> 'arifin@gmail.com',
            'password' => Hash::make('password')
        ]);

        $user->syncRoles('Administrator');
    }
}
