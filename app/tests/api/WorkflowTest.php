<?php

namespace tests\api;

use GuzzleHttp\Client;


class WorkflowTest extends \PHPUnit\Framework\TestCase
{

    const URI_PREFIX = '/api/v1';

    protected static $client;
    protected static $baseUri = 'http://app:8080';
    protected static $workflowId;
    protected static $workflowData;
    

    public static function setUpBeforeClass(): void
    {

        self::$client = new Client([
            'base_uri' => self::$baseUri,
            'http_errors' => false,
            'headers' => ['Accept' => 'application/json'],
        ]);
    }

    public function testCreateWorkflow(): void
    {

        self::$workflowData = [
            'name' => 'Test Workflow ' . time(),
            'description' => 'This is a test workflow.',
            'ownerId' => 'test_owner_id'
        ];

        
        // Ensure the workflow name is unique by appending the current timestamp
        $resp = self::$client->post(self::URI_PREFIX . '/workflow', [
            'json' => self::$workflowData
        ]);

        $this->assertEquals(201, $resp->getStatusCode());
        $responseBody = json_decode($resp->getBody()->getContents(), true);

        $this->assertArrayHasKey('id', $responseBody);


        self::$workflowId = $responseBody['id'];
    }

    /**
     * @depends testCreateWorkflow
     */
    public function testGetWorkflow(): void
    {
        $resp = self::$client->get(self::URI_PREFIX . '/workflow/' . self::$workflowId);

        $this->assertEquals(200, $resp->getStatusCode());
        $responseBody = json_decode($resp->getBody()->getContents(), true);

        $this->assertArrayHasKey('id', $responseBody);
        $this->assertEquals(self::$workflowId, $responseBody['id']);
        $this->assertEquals(self::$workflowData['name'], $responseBody['name']);
        $this->assertEquals(self::$workflowData['description'], $responseBody['description']);
    }


    /**
     * @depends testGetWorkflow
     */
    public function testUpdateWorkflow(): void
    {
        $updatedData = [
            'name' => 'Updated Workflow Name',
            'description' => 'Updated description.',
            'ownerId' => 'updated_owner_id',
            'status' => 'active'
        ];

        $resp = self::$client->put(self::URI_PREFIX . '/workflow/' . self::$workflowId, [
            'json' => $updatedData
        ]);

        $this->assertEquals(200, $resp->getStatusCode());
        $responseBody = json_decode($resp->getBody()->getContents(), true);

        $this->assertArrayHasKey('id', $responseBody);
        $this->assertEquals(self::$workflowId, $responseBody['id']);
        $this->assertEquals($updatedData['name'], $responseBody['name']);
        $this->assertEquals($updatedData['description'], $responseBody['description']);
    }
    


}