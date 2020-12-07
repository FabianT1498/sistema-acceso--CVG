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
        "dni" => "V-26382781",
        "email" => "fabian@cvg.gob.ve",
    ]);
    Worker::create([
        "firstname" => "jesus",
        "lastname" => 'ruiz',
        "dni" => "V-26382782",
        "email" => "jesus@cvg.gob.ve",
    ]);
    Worker::create([
      "firstname" => "maria",
      "lastname" => 'malave',
      "dni" => "V-13090073",
      "email" => "maria@gmail.com",
    ]);
    Worker::create([
      "firstname" => "Andre",
      "lastname" => 'Da silva',
      "dni" => "V-26382783",
      "email" => "bassil@gmail.com",
    ]);
  }
}
