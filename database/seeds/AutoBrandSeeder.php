<?php

use Illuminate\Database\Seeder;
use App\AutoBrand;

class AutoBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	AutoBrand::create([
    		'name' => 'Toyoya',
    		]);
    	AutoBrand::create([
    		'name' => 'Hyundai',
    		]);
    }
}
