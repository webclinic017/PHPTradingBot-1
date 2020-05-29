<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        if(env('APP_ENV') != 'production')
        {
            $password = Hash::make(env('ADMIN_PASS'));
            DB::table('users')->insert([
                'name' => 'Admin',
                'email' => env('ADMIN_LOGIN'),
                'password' =>$password,
            ]);
        }

    }
}
