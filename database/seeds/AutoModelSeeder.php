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
    		'year' => '2011',
    		'auto_brand_id' => 1
    		]);

    	AutoModel::create([
    		'name' => 'Mazda 15',
    		'year' => '2020',
    		'auto_brand_id' => 1
    		]);

    	AutoModel::create([
    		'name' => 'Sedans UV',
    		'year' => '2020',
    		'auto_brand_id' => 2
    		]);

    	AutoModel::create([
    		'name' => 'Sedans UV2.0',
    		'year' => '2021',
    		'auto_brand_id' => 2
    		]);
    }
}