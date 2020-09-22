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
            "firstname" => "jesus",
            "lastname" => 'ruiz',
            "username" => "jesus",
            "dni" => "27296976",
            "email" => "jesus@cvg.gob.ve",
            "password" => \Hash::make("123"),
            "role_id" => "2",
        ]);
        User::create([
            "firstname" => "daniel",
            "lastname" => 'ruza',
            "username" => "daniel",
            "dni" => "12040276",
            "email" => "daniel@cvg.gob.ve",
            "password" => \Hash::make("123"),
            "role_id" => "2",
        ]);

        User::create([
            "firstname" => "Fulano",
            "lastname" => 'Detal',
            "username" => "admin",
            "dni" => "0101010101",
            "email" => "admin@cvg.gob.ve",
            "password" => \Hash::make("123"),
            "role_id" => "1",
        ]);

        User::create([
            "firstname" => "Fulano",
            "lastname" => 'Fulano',
            "username" => "analista",
            "dni" => "03030303",
            "email" => "secretaria@cvg.gob.ve",
            "password" => \Hash::make("123"),
            "role_id" => "4",
        ]);

        User::create([
            "firstname" => "Fulano",
            "lastname" => 'Fulano',
            "username" => "almacenista",
            "dni" => "02020202",
            "email" => "almacenista@cvg.gob.ve",
            "password" => \Hash::make("123"),
            "role_id" => "3",
        ]);
    }
}
