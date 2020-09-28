<?php

use Illuminate\Database\Seeder;
use App\Visitor;

class VisitorSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
      Visitor::create([
          "firstname" => "jesus",
          "lastname" => 'ruiz',
          "dni" => "27296976",
    			"phone_number" => "04148942584"
      ]);
      Visitor::create([
          "firstname" => "fabian",
          "lastname" => 'trillo',
          "dni" => "2798434",
    			"phone_number" => "04148942589"
      ]);
      Visitor::create([
          "firstname" => "Mario",
          "lastname" => 'ruiz',
          "dni" => "2729833",
    			"phone_number" => "04168942584"
      ]);
  }
}
