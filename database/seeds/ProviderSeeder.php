<?php

use Illuminate\Database\Seeder;
use App\Company;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
    		'condition' => 'P',
    		'dni' => "G-95210520",
    		'name' => "Redemerca",
    		'address' => "Alta Vista",
    		'phone' => "0286-7474741",
    		]);
        Company::create([
    		'condition' => 'P',
    		'dni' => "G-825015401",
    		'name' => "Importadora PDV",
    		'address' => "Alta Vista",
    		'phone' => "0286-4141415",
    		]);
        Company::create([
    		'condition' => 'P',
    		'dni' => "G-013645321",
    		'name' => "Importadora Maxxis",
    		'address' => "Alta Vista",
    		'phone' => "0286-2525258",
    		]);
    }
}
