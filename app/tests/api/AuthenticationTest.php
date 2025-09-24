<?php

namespace tests\api;


class AuthenticationTest extends BaseApiTestCase
{


    protected static string $userId;
    protected static array $userData;


    public static function setUpBeforeClass(): void
    {
        self::authenticate();
    }




    public function testCreateUser(): void
    {

        self::$userData = [
            'name' => 'User Test Workflow ' . time(),
            'permissions' => [
                ['role' => 'user']
            ]
        ];


        // Ensure the workflow name is unique by appending the current timestamp
        $resp = self::$client->post($this->getUserBaseURI(), [
            'json' => self::$userData,
            'headers' => [
                'Authorization' => self::$authToken
            ]
        ]);


        $this->assertEquals(201, $resp->getStatusCode());
        $responseBody = json_decode($resp->getBody()->getContents(), true);

        $this->assertArrayHasKey('id', $responseBody);
        $this->assertArrayHasKey('client_id', $responseBody);
        $this->assertArrayHasKey('secret', $responseBody);


        self::$userId = $responseBody['id'];
    }


    protected function getUserBaseURI()
    {
        return sprintf("%s/user", self::URI_PREFIX);
    }






}
