<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiTest extends TestCase
{
    /**
     * Test for Login API
     * - for success login
     */
    public function testApiLoginSuccess() {
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
}
