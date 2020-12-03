<?php

use Illuminate\Database\Seeder;

class BoxStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('box_statuses')->insert([
            'name' => 'Sortie',
            'background_color' => 'yellow',
        ]);

        DB::table('box_statuses')->insert([
            'name' => 'Livrer',
            'background_color' => 'green',
        ]);

        DB::table('box_statuses')->insert([
            'name' => 'Reporter',
            'background_color' => 'orange',
        ]);

        DB::table('box_statuses')->insert([
            'name' => 'Ne réponde pas',
            'background_color' => 'cornflowerblue',
        ]);

        DB::table('box_statuses')->insert([
            'name' => 'Retour',
            'background_color' => 'blueviolet',
        ]);

        DB::table('box_statuses')->insert([
            'name' => 'Annuler',
            'background_color' => 'grey',
        ]);

        DB::table('box_statuses')->insert([
            'name' => 'Payé',
            'background_color' => 'limegreen',
        ]);
    }
}
