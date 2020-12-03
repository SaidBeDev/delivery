<?php

use Illuminate\Database\Seeder;

class ContactTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contact_types')->insert([
            'name' => 'address',
        ]);

        DB::table('contact_types')->insert([
            'name' => 'phone',
        ]);

        DB::table('contact_types')->insert([
            'name' => 'email',
        ]);

        DB::table('contact_types')->insert([
            'name' => 'facebook',
        ]);

        DB::table('contact_types')->insert([
            'name' => 'twitter',
        ]);

        DB::table('contact_types')->insert([
            'name' => 'linkedin',
        ]);

        DB::table('contact_types')->insert([
            'name' => 'instagram',
        ]);

    }
}
