<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTypes extends Model {
    
    protected $table = 'user_types';
    
    protected $fillable = ['name'];
    
}