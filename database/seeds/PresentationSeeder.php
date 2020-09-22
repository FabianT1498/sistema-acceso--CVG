<?php

use Illuminate\Database\Seeder;
use App\Presentation;

class PresentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Presentation::create([
    		'name' => "Paila"
    		]);
        Presentation::create([
    		'name' => "Tambor"
    		]);
        Presentation::create([
    		'name' => "Cara Blanca"
    		]);
    }
}
