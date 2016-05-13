<?php

namespace App\Http\Controllers;


use App\Shop;
use Illuminate\Http\Request;
use Validator;

class ShopsController extends Controller
{
    public function get_shops(Request $request){
        $this->validate($request, [
            'location.lat'  => 'required',
            'location.lng'  => 'required',
            'page.index'    => 'required|integer',
            'page.size'     => 'integer',
            'category'      => 'required|integer'
        ]);

        
    }
}
    