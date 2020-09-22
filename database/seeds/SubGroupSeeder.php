<?php

use Illuminate\Database\Seeder;
use App\SubGroup;

class SubGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubGroup::create([
        	'group_id' => 1,
        	'name' => "Aceite"
        	]);
        SubGroup::create([
        	'group_id' => 1,
        	'name' => "Grasa"
        	]);
        SubGroup::create([
        	'group_id' => 2,
        	'name' => "Carga Pesada"
        	]);
        SubGroup::create([
        	'group_id' => 2,
        	'name' => "Tractor"
        	]);
        SubGroup::create([
        	'group_id' => 2,
        	'name' => "Carro"
        	]);
    }
}
