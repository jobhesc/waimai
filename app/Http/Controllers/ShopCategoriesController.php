<?php

namespace App\Http\Controllers;

use App\ShopCategory;
use Illuminate\Http\Request;

use App\Http\Requests;

class ShopCategoriesController extends Controller
{
    public function get_categories(Request $request){
        return ShopCategory::all();
    }
}
