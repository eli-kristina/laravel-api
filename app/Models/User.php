<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    
    protected $table = 'users';
    
    /**
     * Attribute assign
     */
    protected $fillable = ['email', 'user_type_id', 'password'];
    
    /**
     * Attribute hidden
     */
    protected $hidden = ['password'];
    
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims() {
        return [];
    }
}
