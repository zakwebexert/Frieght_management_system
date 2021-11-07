<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin','plan','active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function account_detail(){
        return $this->hasMany(account_detail::class,'customer_id');
    }

    public function address(){
        return $this->hasMany(customer_address::class,'customer_id');
    }

    public function other_contact(){
        return $this->hasOne(other_contact::class,'customer_id');
    }

    public function primary_contact(){
        return $this->hasOne(primary_contact::class,'customer_id');
    }

    public function secondary_contact(){
        return $this->hasOne(secondary_contact::class,'customer_id');
    }

    public function sites(){
        return $this->hasMany(sites::class,'customer_id');
    }

    public function notes(){
        return $this->hasMany(notes::class,'customer_id');
    }

    public function attachments(){
        return $this->hasOne(attachments::class,'customer_id');
    }


}
