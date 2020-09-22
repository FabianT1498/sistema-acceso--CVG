<?php

use Illuminate\Database\Seeder;
use App\Location;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Location::create([
    		'name' => "Almacen Ferrominera"
    		]);
    	Location::create([
    		'name' => "Almacen Sidor"
    		]);
    	Location::create([
    		'name' => "Almacen Alcasa"
    		]);
    	Location::create([
    		'name' => "Almacen Bauxilum"
    		]);
        
    }
}
