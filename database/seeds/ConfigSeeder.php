<?php

use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('configs')->insert([
            'name'    => 'delivery_price',
            'content' => '500'
        ]);
    }
}
