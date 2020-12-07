<?php

use Illuminate\Database\Seeder;
use App\AutoModel;

class AutoModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	AutoModel::create([
    		'name' => 'MAZDA 12',
    		'auto_brand_id' => 1
    		]);

    	AutoModel::create([
    		'name' => 'MAZDA 15',
    		'auto_brand_id' => 1
    		]);

    }
}