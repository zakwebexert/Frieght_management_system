<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer_address extends Model
{
    use HasFactory;

    protected $table = 'customer_address';

    protected $fillable = ['customer_id','p_address_line_1','p_address_line_2','p_suburb','p_postal_code','p_state','p_opening_time',
        'r_address_line_1','r_address_line_2','r_suburb','r_postal_code','r_state','r_opening_time',
        'b_address_line_1','b_address_line_2','b_suburb','b_postal_code','b_state','b_opening_time'];

    public function user(){
        return $this->belongsTo(User::class,'customer_id');
    }

}
