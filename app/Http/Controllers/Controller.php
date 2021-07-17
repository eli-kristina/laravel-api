<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\UserTypes;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    protected function checkIsStaff($user) {
        $type = UserTypes::where('id', $user->user_type_id)->first('name');
        
        if (!empty($type) && strtolower($type->name) == 'staff') {
            return true;
        }
        
        return false;
    }
}
