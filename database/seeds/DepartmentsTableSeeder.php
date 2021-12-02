<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('departments')->truncate();
        DB::table('departments')->insert([
            'name' => 'Specialties & GT'
        ]);
        DB::table('departments')->insert([
            'name' => 'Operations'
        ]);
        DB::table('departments')->insert([
            'name' => 'Network'
        ]);
        DB::table('departments')->insert([
            'name' => 'HR, Admin & Legal'
        ]);
        DB::table('departments')->insert([
            'name' => 'Finance'
        ]);
    }
}
