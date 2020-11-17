<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(RoleSeeder::class);
         $this->call(WorkerSeeder::class);
         $this->call(UserSeeder::class);
         $this->call(VisitorSeeder::class);
         $this->call(AutoBrandSeeder::class);
         $this->call(AutoModelSeeder::class);
        // $this->call(AutoSeeder::class);
         
    }
}