<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class other_contact extends Model
{
    use HasFactory;

    protected $table = 'customer_other_contacts';

    protected $fillable = ['customer_id','contact_name','position','mobile','office_phone','fax','email'];


    public function user(){
        return $this->belongsTo(User::class,'customer_id');
    }
}
