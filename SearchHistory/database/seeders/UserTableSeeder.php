<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
             DB::table('users')->insert([
            [
             'name' => 'tajul',
            'email' => 'tajul@gmail.com',
            'password' => bcrypt('123456'),
            ],
            [
             'name' => 'rakib',
            'email' => 'rakib@gmail.com',
            'password' => bcrypt('123456'),
            ],
            [
             'name' => 'harun',
            'email' => 'harun@gmail.com',
            'password' => bcrypt('123456'),
            ],
            [
             'name' => 'rabbi',
            'email' => 'rabbi@gmail.com',
            'password' => bcrypt('123456'),
            ],
            [
             'name' =>  'abdur rahim',
            'email' => 'abdur_rahim@gmail.com',
            'password' => bcrypt('123456'),
            ],

        ]);
    }
}
