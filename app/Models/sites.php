<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sites extends Model
{
    use HasFactory;

    protected $table = 'customer_sites';

    protected $fillable = ['customer_id','site_name','address_line_1','address_line_2','suburb','postal_code','state'];
}
