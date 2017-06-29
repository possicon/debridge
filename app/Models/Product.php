<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function inventory()
    {
    	return $this->belongsTo('App\Models\Inventory');
    }

    public function category()
    {
    	return $this->belongsTo('App\Models\ProductCategory');
    }

    public function product_of_the_week() //product_of_the_week
    {
    	return $this->hasOne('App\Models\ProductOfTheWeek');
    }

    //hottest product relationships
    public function hottest()
    {
        return $this->belongsTo('App\Models\HottestProduct', 'hottest_product_id');
    }

    public function admires()
    {
        return $this->hasMany('App\Models\ProductAdmire');
    }

    public function promo()
    {
        return $this->hasOne('App\Model\ProductPromo');
    }

    public function pictures()
    {
        return $this->belongsTo('App\Models\PhotoAlbum');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\ProductNotification');
    }

    public function hypes()
    {
        return $this->hasMany('App\Models\ProductHype');
    }

    public function cart(){
        $this->belongsTo('App\Models\Cart');
    }
}