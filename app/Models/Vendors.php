<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendors extends Model
{
    use HasFactory;

    protected $table = 'vendor';
    protected $fillable = [
        'name', 'mobile_no', 'email', 'shop_name', 'website', 'shop_email', 'description', 'category_id', 'location', 'lat', 'long', 'status', 'is_blocked'
    ];
}
