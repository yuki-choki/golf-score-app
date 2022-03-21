<?php

use Illuminate\Database\Seeder;

class CorseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        exec('php artisan command:updateCourseDataCommand');
    }
}
