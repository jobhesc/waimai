<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCategoryCross extends Model
{
    public function shop(){
        return $this->belongsTo('App\Shop');
    }
    
    public function shop_category(){
        return $this->belongsTo('App\ShopCategory');
    }
}
