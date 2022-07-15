<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralAmt extends Model
{
    use HasFactory;

    protected $table = 'referral_amts';
    protected $fillable = [
        'referral_amt', 'status'
    ];
}
