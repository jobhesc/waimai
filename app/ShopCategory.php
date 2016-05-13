<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    //可以被批量赋值的属性
    protected $fillable = ['name', 'parent_id'];

    //在数组中隐藏的属性
    protected $hidden = ['created_at', 'updated_at'];

    public function shops(){
        return $this->belongsToMany('App\Shop', 'shop_category_crosses');
    }
}
