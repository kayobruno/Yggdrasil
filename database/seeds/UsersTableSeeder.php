<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $userId = DB::table('users')->insertGetId([
            'name' => 'Kayo Bruno',
            'email' => 'kayodw@gmail.com',
            'password' => bcrypt('12345678'),
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        ]);

        DB::table('role_user')->insert([
            'user_id' => $userId,
            'role_id' => 1
        ]);
    }
}
