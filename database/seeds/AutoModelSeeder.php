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
    		'name' => 'Mazda 12',
    		'auto_brand_id' => 1
    		]);

    	AutoModel::create([
    		'name' => 'Mazda 15',
    		'auto_brand_id' => 1
    		]);

    	AutoModel::create([
    		'name' => 'Sedans UV',
    		'auto_brand_id' => 2
    		]);

    	AutoModel::create([
    		'name' => 'Sedans UV2.0',
    		'auto_brand_id' => 2
    		]);
    }
}