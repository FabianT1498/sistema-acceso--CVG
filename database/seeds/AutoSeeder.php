<?php

use Illuminate\Database\Seeder;
use App\Auto;

class AutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
    	Auto::create([
    		'enrrolment' => 'ABC123',
    		'color' => 'rojo',
    		'visitor_id' => 1,
    		'auto_model_id' => 3
    		]);
    }
}
