<?php

namespace tests\api;

use \GuzzleHttp\Client;

class BaseApiTestCase extends \PHPUnit\Framework\TestCase
{
    public const URI_PREFIX = '/api/v1';
    protected static Client $client;
    protected static string $baseUri = 'http://app:8080';
    protected static string $authToken;
    protected static array $creds;

    public static function setUpBeforeClass(): void
    {
        self::$client = new Client([
            'base_uri' => self::$baseUri,
            'http_errors' => false
        ]);

        self::$creds = [
            'X-ACCESS-TOKEN' => getenv('AUTH_TEST_ADMIN_ACCESS_TOKEN'),
            'X-ACCESS-CHECK' => getenv('AUTH_TEST_ADMIN_ACCESS_CHECK'),
        ];
    }


    protected static function authenticate() {
        self::$client = new Client([
            'base_uri' => self::$baseUri,
            'http_errors' => false,
            'headers' => ['Accept' => 'application/json'],
        ]);


        // First step is to create a new Token for this user:
        // AUTH_ADMIN_TEST_CLIENT_ID
        // AUTH_ADMIN_TEST_CLIENT_SECRET

        $clientId = getenv('AUTH_ADMIN_TEST_CLIENT_ID');
        $clientSecret = getenv('AUTH_ADMIN_TEST_CLIENT_SECRET');


        $payload = [
            'json' => [
                'client_id' => $clientId,
                'secret' => $clientSecret
            ],
        ];


        $credsResponse = self::$client->post(self::URI_PREFIX.'/auth/credentials', $payload);

        assert($credsResponse->getStatusCode() === 200);

        // Now we have to use these credentials to get a new Auth Token
        // AUTH_TEST_ADMIN_ACCESS_TOKEN
        // AUTH_TEST_ADMIN_ACCESS_CHECK

        $jsonCredentials = (string) $credsResponse->getBody();
        $tokenData = json_decode($jsonCredentials, true);

        $accessToken = $tokenData['X-ACCESS-TOKEN'] ?? '';
        $accessCheck = $tokenData['X-ACCESS-CHECK'] ?? '';

        self::$creds = [
                'X-ACCESS-TOKEN' => $accessToken,
                'X-ACCESS-CHECK' => $accessCheck,
        ];

        $payload = [
            'headers' => self::$creds
            ];
        // Authenticate
        $authResponse = self::$client->post(self::URI_PREFIX.'/auth', $payload);

        assert($authResponse->getStatusCode() === 200);

        // Authorization
        $authToken = $authResponse->getHeader('Authorization');


        self::$authToken = 'Bearer '. $authToken[0];        
    }    
}