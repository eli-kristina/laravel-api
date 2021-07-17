<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversationDetails extends Model {
    
    protected $table = 'conversation_details';
    
    protected $fillable = ['conversation_id', 'sender_user_id', 'message'];
    
}