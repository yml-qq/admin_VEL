<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'UserName'=>'admin',
                'PassWord'=>bcrypt('yml1006'),
                'Phone'=>'17693012484',
                'Email'=>'ymenglong@foxmail.com',
                'role_id'=>'1'
            ]
        ];
        DB::table('users')->insert($data);
    }
}
