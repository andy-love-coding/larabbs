<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
		$this->call(TopicsTableSeeder::class);
		$this->call(ReplysTableSeeder::class); // ReplysTableSeeder 应该在 TopicTableSeeder 之后
    }
}
