<?php

namespace tests\api;

use GuzzleHttp\Client;


class WorkflowTaskTest extends \PHPUnit\Framework\TestCase
{

    const URI_PREFIX = '/api/v1';

    protected static $client;
    protected static $baseUri = 'http://app:8080';
    protected static $workflowId;
    protected static $workflowData;
    protected static $taskData;
    protected static $taskId;

    public static function setUpBeforeClass(): void
    {

        self::$client = new Client([
            'base_uri' => self::$baseUri,
            'http_errors' => false,
            'headers' => ['Accept' => 'application/json'],
        ]);


        self::$workflowData = [
            'name' => 'Test Workflow ' . time(),
            'description' => 'This is a test workflow.',
            'ownerId' => 'test_owner_id'
        ];

        
        // Ensure the workflow name is unique by appending the current timestamp
        $resp = self::$client->post(self::URI_PREFIX . '/workflow', [
            'json' => self::$workflowData
        ]);

        $responseBody = json_decode($resp->getBody()->getContents(), true);
        assert($resp->getStatusCode() === 201, 'Expected status code 201, got ' . $resp->getStatusCode());
        assert(isset($responseBody['id']), 'Response body does not contain "id" key');

        self::$workflowId = $responseBody['id'];
    }

    public function testCreateTaskWorkflow(): void
    {

        self::$taskData = [
            'name' => 'Test Task ' . time(),
            'description' => 'This is a test task.',
            'ownerId' => 'test_owner_id',
            'workflowId' => self::$workflowId
        ];

        // Ensure the task name is unique by appending the current timestamp
        $resp = self::$client->post(self::URI_PREFIX . '/workflow/' . self::$workflowId . '/task', [
            'json' => self::$taskData
        ]);

        $this->assertEquals(201, $resp->getStatusCode());
        $responseBody = json_decode($resp->getBody()->getContents(), true);

        $this->assertArrayHasKey('id', $responseBody);
        self::$taskId = $responseBody['id'];        
    }


}