<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * Test for Login API
     * - for failed login
     */
    public function test_login_api_failed() {
        echo "1 test_login_api_failed!!!";
        
        $params = [
            'email' => 'customer@gmail.com',
            'password' => 'password'
        ];
                
        $this->json('POST', 'api/login', $params, ['Accept' => 'application/json'])
            ->assertStatus(401)
            ->assertJsonStructure([
                "error",
                "message"
            ]);
    }
    
    /**
     * Test for Login API
     * - for success login
     */
    public function test_login_api_success() {
        echo "2 test_login_api_success!!!";
        
        $params = [
            'email' => 'customer@gmail.com',
            'password' => 'dummydummy'
        ];
                
        $this->json('POST', 'api/login', $params, ['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure([
                "error",
                "message",
                "data" => [
                    "user" => [
                        "id",
                        "email",
                        "user_type_id",
                        "token"
                    ]
                ]
            ]);
    }
    
    /**
     * Test for Logout API without headers
     */
    public function test_logout_api_failed() {
        echo "3 test_logout_api_failed!!!";
        
        $this->json('GET', 'api/logout')
            ->assertStatus(401)
            ->assertJson([
                "error" => 1,
                "message" => 'Authorization Token not found'
            ]);
    }
    
    /**
     * Test for Logout API
     */
    public function test_logout_api_success() {
        echo "4 test_logout_api_success!!!";
        
        $params = [
            'email' => 'customer@gmail.com',
            'password' => 'dummydummy'
        ];
                
        $login = $this->json('POST', 'api/login', $params, ['Accept' => 'application/json']);
        $token = $login["data"]["user"]["token"];
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('GET', 'api/logout')
            ->assertStatus(200)
            ->assertJsonStructure([
                "error",
                "message"
            ]);
    }
    
    public function test_send_message_api_success() {
        echo "5 test_send_message_api_success!!!";
        
        /* create other user */
        User::create([
            'email' => 'testeruser@gmail.com',
            'password' => 'dummydummy',
            'user_type_id' => 1
        ]);
        
        $params = [
            'email' => 'customer@gmail.com',
            'password' => 'dummydummy'
        ];
                
        $login = $this->json('POST', 'api/login', $params, ['Accept' => 'application/json']);
        $token = $login["data"]["user"]["token"];
        
        
        $params = [
            'to_user_id' => 3,
            'message' => 'hello, how are you?'
        ];
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('POST', 'api/conversations', $params, ['Authorization' => 'Bearer ' . $token])
            ->assertStatus(200)
            ->assertJsonStructure([
                "error",
                "message"
            ]);
    }
    
    public function test_chat_history_api_success() {
        echo "6 test_chat_history_api_success!!!";
        
        $params = [
            'email' => 'customer@gmail.com',
            'password' => 'dummydummy'
        ];
                
        $login = $this->json('POST', 'api/login', $params, ['Accept' => 'application/json']);
        $token = $login["data"]["user"]["token"];
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('GET', 'api/conversations', ['Authorization' => 'Bearer ' . $token])
            ->assertStatus(200)
            ->assertJsonStructure([
                "error",
                "message",
                "data"
            ]);
    }
    
    public function test_chat_history_detail_api_success() {
        echo "7 test_chat_history_detail_api_success!!!";
        
        $params = [
            'email' => 'customer@gmail.com',
            'password' => 'dummydummy'
        ];
                
        $login = $this->json('POST', 'api/login', $params, ['Accept' => 'application/json']);
        $token = $login["data"]["user"]["token"];
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('GET', 'api/conversations/1', ['Authorization' => 'Bearer ' . $token])
            ->assertStatus(200)
            ->assertJsonStructure([
                "error",
                "message",
                "data"
            ]);
    }
    
    public function test_customer_api_success() {
        echo "8 test_customer_api_success!!!";
        
        $params = [
            'email' => 'staff@gmail.com',
            'password' => 'dummydummy'
        ];
                
        $login = $this->json('POST', 'api/login', $params, ['Accept' => 'application/json']);
        $token = $login["data"]["user"]["token"];
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('GET', 'api/customers', ['Authorization' => 'Bearer ' . $token])
            ->assertStatus(200)
            ->assertJsonStructure([
                "error",
                "message",
                "data"
            ]);
    }
    
    public function test_delete_customer_api_success() {
        echo "9 test_delete_customer_api_success!!!";
        
        $params = [
            'email' => 'staff@gmail.com',
            'password' => 'dummydummy'
        ];
                
        $login = $this->json('POST', 'api/login', $params, ['Accept' => 'application/json']);
        $token = $login["data"]["user"]["token"];
        
        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->json('DELETE', 'api/customers/3', ['Authorization' => 'Bearer ' . $token])
            ->assertStatus(200)
            ->assertJsonStructure([
                "error",
                "message"
            ]);
    }
}
