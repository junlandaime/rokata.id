<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Jundi Abdullah',
            'email' => 'jdi.jundi99@gmail.com',
            'password' => bcrypt('secret')

        ]);
    }
}
