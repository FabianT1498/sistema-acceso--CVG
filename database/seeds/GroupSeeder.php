<?php

use Illuminate\Database\Seeder;
use App\Group;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create([
        	'name' => "Lubricante"
        	]);

        Group::create([
        	'name' => "Caucho"
        	]);

        Group::create([
        	'name' => "Repuesto"
        	]);
    }
}
