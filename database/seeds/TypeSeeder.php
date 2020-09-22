<?php

use Illuminate\Database\Seeder;
use App\Type;


class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Type::create([
    		'sub_group_id' => 1,
    		'name' => "25w/40"
    		]);

		Type::create([
			'sub_group_id' => 1,
    		'name' => "20w/50"
    		]);

		Type::create([
    		'sub_group_id' => 5,
    		'name' => "185/65/14"
    		]);

		Type::create([
    		'sub_group_id' => 5,
    		'name' => "185/60/14"
    		]);

    }
}
