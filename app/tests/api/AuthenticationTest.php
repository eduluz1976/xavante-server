<?php

namespace tests\api;

use GuzzleHttp\Client;

class AuthenticationTest extends \PHPUnit\Framework\TestCase
{
    public const URI_PREFIX = '/api/v1';

    protected static $client;
    protected static $baseUri = 'http://app:8080';
    protected static $userId;
    protected static $userData;
    protected static $countRows = 0;
    protected static $authToken;


    public static function setUpBeforeClass(): void
    {

        self::$client = new Client([
            'base_uri' => self::$baseUri,
            'http_errors' => false,
            'headers' => ['Accept' => 'application/json'],
        ]);

        // Authenticate
        $authResponse = self::$client->post(self::URI_PREFIX.'/auth', [
            'headers' => [
                'X-ACCESS-TOKEN' => getenv('AUTH_TEST_ADMIN_ACCESS_TOKEN'),
                'X-ACCESS-CHECK' => getenv('AUTH_TEST_ADMIN_ACCESS_CHECK'),
            ]
        ]);

        assert($authResponse->getStatusCode() === 200);

        // Authorization
        $authToken = $authResponse->getHeader('Authorization');

        self::$authToken = 'Bearer '. $authToken[0];
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
