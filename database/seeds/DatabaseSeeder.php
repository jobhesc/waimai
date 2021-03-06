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
        $this->call(UserTableSeeder::class);
        $this->call(ShopCategoryTableSeeder::class);
        $this->call(ShopTableSeeder::class);
        $this->call(ShopCategoryCrossTableSeeder::class);
    }
}
