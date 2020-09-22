<?php

use Illuminate\Database\Seeder;
use App\Role;
class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'ADMIN'
        ]);

        Role::create([
            'name' => 'SUPERADMIN'
        ]);

        Role::create([
            'name' => 'ALMACENISTA'
        ]);
        
        Role::create([
            'name' => 'ANALISTA'
        ]);
    }
}
