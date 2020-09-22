<?php

use Illuminate\Database\Seeder;
use App\Item;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Item::create([
    		'type_id' => 1,
    		'presentation_id' => 1,
    		'description' => "Etiqueta amarilla",
    		]);
        Item::create([
    		'type_id' => 1,
    		'presentation_id' => 2,
    		'description' => "Especial",
    		]);
        Item::create([
    		'type_id' => 3,
    		'presentation_id' => 3,
    		'description' => "GoodYear",
    		]);
    }
}
