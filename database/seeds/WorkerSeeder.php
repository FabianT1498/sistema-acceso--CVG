<?php

use App\Worker;
use Illuminate\Database\Seeder;

class WorkerSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Worker::create([
        "firstname" => "fabian",
        "lastname" => 'trillo',
        "dni" => "2798434",
        "email" => "fabian@cvg.gob.ve",
    ]);
    Worker::create([
        "firstname" => "jesus",
        "lastname" => 'ruiz',
        "dni" => "27296976",
        "email" => "jesus@cvg.gob.ve",
    ]);
    Worker::create([
      "firstname" => "pedro",
      "lastname" => 'perez',
      "dni" => "25393650",
      "email" => "pedro@gmail.com",
    ]);
    Worker::create([
      "firstname" => "ibrahim",
      "lastname" => 'diaz',
      "dni" => "25392354",
      "email" => "ibrahim@gmail.com",
    ]);
    Worker::create([
      "firstname" => "alex",
      "lastname" => 'lopz',
      "dni" => "253936233",
      "email" => "alex@gmail.com",
    ]);
  }
}
