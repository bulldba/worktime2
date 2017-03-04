<?php

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
        DB::table('pros')->insert(['name' => 'default']);
        DB::table('tags')->insert(['name' => 'default', 'pro' => 1]);

        DB::table('titles')->insert([
            ['id' => 100, 'name' => '需求', 'caty' => 1, 'locked' => 1],
            ['id' => 101, 'name' => '改进', 'caty' => 1, 'locked' => 1],
            ['id' => 102, 'name' => 'BUG', 'caty' => 1, 'locked' => 1],
            ['id' => 201, 'name' => 'ICHECK', 'caty' => 1, 'locked' => 1],
            ['id' => 202, 'name' => 'CHECK', 'caty' => 1, 'locked' => 1],

            ['id' => 300, 'name' => '管理', 'caty' => 2, 'locked' => 1],
            ['id' => 301, 'name' => '策划', 'caty' => 2, 'locked' => 1],
            ['id' => 302, 'name' => '美术', 'caty' => 2, 'locked' => 1],
            ['id' => 303, 'name' => '程序', 'caty' => 2, 'locked' => 1],
            ['id' => 305, 'name' => 'QA', 'caty' => 2, 'locked' => 1],
            ['id' => 306, 'name' => '其他', 'caty' => 2, 'locked' => 1]

        ]);
    }
}
