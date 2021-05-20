<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'ゲスト',
            'email' => 'guest@gmail.com',
            'password' => Hash::make('password'),
            'update_job' => 'UserSeeder'
        ]);
        DB::table('users')->insert([
            'name' => '同伴者A',
            'parent_user_id' => 1,
            'update_job' => 'UserSeeder'
            ]);
        DB::table('users')->insert([
            'name' => '同伴者B',
            'parent_user_id' => 1,
            'update_job' => 'UserSeeder'
        ]);
    }
}
