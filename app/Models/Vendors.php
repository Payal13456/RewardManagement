<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    use HasFactory;

    protected $table = 'vendor';
    protected $fillable = [
        'name', 'phone_code','mobile_no', 'email', 'shop_name', 'website', 'description', 'category_id', 'location', 'lat', 'long', 'shop_logo', 'status', 'is_blocked'
    ];

    public static function getShopImagePath ($img)
    {
        if(!empty($img)) 
            if(\File::exists(public_path('/uploads/shop/logo/'.$img.'')))
                return asset('public/uploads/shop/logo/'.$img.'');

            else 
                return asset('public/uploads/shop/logo/no-image.png');
        else
            return asset('public/uploads/shop/logo/no-image.png');
    }
}
