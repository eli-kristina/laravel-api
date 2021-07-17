<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversations extends Model {
    
    protected $table = 'conversations';
    
    protected $fillable = ['from_user_id', 'to_user_id', 'last_message'];
    
}