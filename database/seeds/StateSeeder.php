<?php

use Illuminate\Database\Seeder;
use App\State;
class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        State::create([
            'name' => 'ENTREGRADA',
            'type' => 'INVOICE'
        ]);
        State::create([
            'name' => 'ANULADA',
            'type' => 'INVOICE'
        ]);
        State::create([
            'name' => 'DEVUELTA',
            'type' => 'INVOICE'
        ]);
        State::create([
            'name' => 'POR CONFIRMAR',
            'type' => 'INVOICE'
        ]);
        State::create([
            'name' => 'CONFIRMADA',
            'type' => 'INVOICE'
        ]);
        State::create([
            'name' => 'PENDIENTE',
            'type' => 'INVENTORY'
        ]);
        State::create([
            'name' => 'REALIZADO',
            'type' => 'INVENTORY'
        ]);
    }
}
