<?php

use DB;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        DB::table('pros')->insert(['name' => 'default']);
        DB::table('tags')->insert(['name' => 'default', 'pro' => 1]);

        Model::reguard();
    }
}
