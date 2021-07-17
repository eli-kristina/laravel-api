<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    /**
     * Function to login
     * 
     * POST: /api/login
     * PARAMS:
     * - email      string (required)
     * - password   string (required)
     */
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        try {
            if ($token = JWTAuth::attempt($credentials)) {
                $user = $request->user();
                $data = [
                    "user" => [
                        "id"            => $user->id,
                        "email"         => $user->email,
                        "user_type_id"  => $user->user_type_id,
                        "token"         => $token
                    ]
                ];
                
                return response()->json(['error' => 0, 'message' => '', 'data' => $data], 200);
            } else {
                return response()->json(['error' => 1, 'message' => 'invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 1, 'message' => 'could not create token'], 500);
        }
    }
    
    /**
     * Function to logout
     * 
     * GET: /api/logout
     * HEADERS:
     * - Authorization  Bearer {token}
     */
    public function logout() {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            
            return response()->json(['error' => 0, 'message' => 'logout success'], 200);
        } catch (JWTException $e) {
            return response()->json(['error' => 1, 'message' => 'could not logout'], 500);
        }
    }
    
    /**
     * Function to get list customer
     * 
     * GET: /api/customers
     * HEADERS:
     * - Authorization  Bearer {token}
     */
    public function list_customers(Request $request) {
        $user = $request->user();
        $is_staff = $this->checkIsStaff($user);
        
        if ($is_staff) {
            $query = User::join('user_types', 'user_types.id', '=', 'users.user_type_id')
                ->where('user_types.name', 'Customer')
                ->get(['users.id', 'users.email']);
                
            if (!empty($query)) {
                $data = [];
                
                foreach($query as $val) {
                    $data[] = [
                        'user_id'   => $val->id,
                        'email'     => $val->email
                    ];
                }
                
                return response()->json(['error' => 0, 'message' => '', 'data' => ['customers' => $data]], 200);
            }
        }
        
        return response()->json(['error' => 1, 'message' => 'customers not found'], 404);
    }
    
    /**
     * Function to delete customer
     * 
     * DELETE: /api/customers/{id}
     * HEADERS:
     * - Authorization  Bearer {token}
     */
    public function delete_customers($id, Request $request) {
        $user = $request->user();
        $is_staff = $this->checkIsStaff($user);
        
        if ($is_staff) {
            $check = User::where('id', $id)->first();
            
            if (!$this->checkIsStaff($check)) {
                User::where('id', $id)->delete();
                
                return response()->json(['error' => 0, 'message' => 'customer deleted'], 200);
            }
        }
        
        return response()->json(['error' => 1, 'message' => 'customers not found'], 404);
    }
}
