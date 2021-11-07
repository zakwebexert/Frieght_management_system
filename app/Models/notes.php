<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class notes extends Model
{
    use HasFactory;

    protected $table = "customer_notes";

    protected $fillable = ['customer_id','note','auther','date'];
}
