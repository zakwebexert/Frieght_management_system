<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class account_detail extends Model
{
    use HasFactory;

    protected $table = 'customer_account_details';

    protected $fillable = ['customer_id','business_name','tading_name','account_manager','account_status','account_code',
        'industry','ABN','ACN','payment_terms','credit_limit','billing_method','review_date'];

    public function user(){
        return $this->belongsTo(User::class,'customer_id');
    }
}
