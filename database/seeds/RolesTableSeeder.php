<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'name' => 'super-admin',
                'display_name' => 'Super Administrador',
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            ],
        ]);
    }
}
