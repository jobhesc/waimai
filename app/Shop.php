<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    /**
     * 获取属于该店铺的所有订单
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders(){
        return $this->hasMany('App\Order');
    }

    public function shop_categories(){
        return $this->belongsToMany('App\ShopCategory', 'shop_category_crosses');
    }
}
