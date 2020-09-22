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
         $this->call(UserSeeder::class);
         $this->call(StateSeeder::class);
         $this->call(GroupSeeder::class);
         $this->call(SubGroupSeeder::class);
         $this->call(TypeSeeder::class);
         $this->call(PresentationSeeder::class);
         $this->call(ItemSeeder::class);
         $this->call(ProviderSeeder::class);
         $this->call(LocationSeeder::class);
         $this->call(CompanySeeder::class);
    }
}