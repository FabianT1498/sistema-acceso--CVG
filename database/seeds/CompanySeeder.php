<?php

use Illuminate\Database\Seeder;
use App\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Company::create([
    		'condition' => 'C',
    		'dni' => "G-123456789",
    		'name' => "CVG",
    		'address' => "Alta Vista",
    		'phone' => "0286-9663539",
    		]);
        Company::create([
    		'condition' => 'C',
    		'dni' => "G-98574252",
    		'name' => "CVG ALCASA",
    		'address' => "Los Pinos",
    		'phone' => "0286-9658585",
    		]);
        Company::create([
    		'condition' => 'C',
    		'dni' => "G-74185209",
    		'name' => "CVG VENALUM",
    		'address' => "Los Pinos",
    		'phone' => "0286-9685209",
    		]);
    }
}
