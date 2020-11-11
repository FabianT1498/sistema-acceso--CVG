<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "username" => "jesus",
            "password" => \Hash::make("123"),
            "role_id" => "2",
            "worker_id" => "1"
        ]);

        User::create([
            "username" => "fabiant98",
            "password" => \Hash::make("123"),
            "role_id" => "1",
            "worker_id" => "2"
        ]);
    }
}
