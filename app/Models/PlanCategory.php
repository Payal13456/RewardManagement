<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanCategory extends Model
{
    use HasFactory;

    protected $table = 'plan_categories';
    protected $fillable = ['plan_id', 'category_id'];
}
