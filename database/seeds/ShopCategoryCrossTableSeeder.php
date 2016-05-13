<?php

use Illuminate\Database\Seeder;

class ShopCategoryCrossTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shops = App\Shop::all();
        $categories = App\ShopCategory::whereNotNull('parent_id')->lists('id');
        var_dump($categories);
        $shops->each(function($shop) use($categories){
            $count = (int)($categories->count()/5);
            $random = $categories->random(rand(1, $count));
            $shop->shop_categories()->attach([1,3,5]);
        });
    }
}
