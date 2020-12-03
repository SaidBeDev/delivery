<?php

use Illuminate\Database\Seeder;

class profileTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('profile_types')->insert([
            'name' => 'superAdmin',
        ]);

        DB::table('profile_types')->insert([
            'name' => 'distributor',
        ]);

        DB::table('profile_types')->insert([
            'name' => 'client',
        ]);

        DB::table('profile_types')->insert([
            'name' => 'deliveryMan',
        ]);
    }
}
