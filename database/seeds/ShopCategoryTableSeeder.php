<?php

use App\ShopCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ShopCategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();
        $category = ShopCategory::create(['name' => '快餐便当']);
        ShopCategory::create(['name' => '盖浇饭', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '香锅砂锅', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '麻辣烫', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '包子粥店', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '米粉面馆', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '生煎锅贴', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '汉堡', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '饺子混沌', 'parent_id' => $category->id]);

        $category = ShopCategory::create(['name' => '特色菜系']);
        ShopCategory::create(['name' => '海鲜', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '川湘菜', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '火锅烤鱼', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '其他菜系', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '东北菜', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '清真', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '粤菜', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '云南菜', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '西北菜', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '江浙菜', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '鲁菜', 'parent_id' => $category->id]);

        $category = ShopCategory::create(['name' => '异国料理']);
        ShopCategory::create(['name' => '披萨意面', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '日韩料理', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '西餐', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '东南亚菜', 'parent_id' => $category->id]);

        $category = ShopCategory::create(['name' => '小吃夜宵']);
        ShopCategory::create(['name' => '小龙虾', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '鸭脖卤味', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '炸鸡炸串', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '地方小吃', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '烧烤', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '零食', 'parent_id' => $category->id]);

        $category = ShopCategory::create(['name' => '甜品饮品']);
        ShopCategory::create(['name' => '甜品', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '奶茶果汁', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '咖啡', 'parent_id' => $category->id]);

        $category = ShopCategory::create(['name' => '果蔬生鲜']);
        ShopCategory::create(['name' => '水果', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '生鲜', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '蔬菜', 'parent_id' => $category->id]);

        $category = ShopCategory::create(['name' => '鲜花蛋糕']);
        ShopCategory::create(['name' => '蛋糕', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '鲜花', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '面包', 'parent_id' => $category->id]);

        $category = ShopCategory::create(['name' => '商店超市']);
        ShopCategory::create(['name' => '超市', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '水站', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '奶站', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '粮油', 'parent_id' => $category->id]);
        ShopCategory::create(['name' => '茶', 'parent_id' => $category->id]);
    }
}
