<?php

namespace tests\api;


class WorkflowTaskTest extends BaseApiTestCase
{

    protected static string $workflowId;
    protected static array $workflowData;
    protected static array $taskData;
    protected static string $taskId;

    public static function setUpBeforeClass(): void
    {

        self::authenticate();
        self::createWorkflow();        
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
        $uri = self::URI_PREFIX . '/workflow/' . self::$workflowId . '/task';
        $resp = self::$client->post($uri, [
            'json' => self::$taskData,
            'headers' => [
                'Authorization' => self::$authToken
            ]
        ]);

        $this->assertEquals(201, $resp->getStatusCode());
        $responseBody = json_decode($resp->getBody()->getContents(), true);

        $this->assertArrayHasKey('id', $responseBody);
        self::$taskId = $responseBody['id'];
    }



    public static function createWorkflow() {
                self::$workflowData = [
            'name' => 'Test Workflow ' . time() . microtime(true),
            'description' => 'This is a test workflow. ',
            'ownerId' => 'test_owner_id'
        ];


        // Ensure the workflow name is unique by appending the current timestamp
        $resp = self::$client->post(self::URI_PREFIX . '/workflow', [
            'json' => self::$workflowData,
            'headers' => [
                'Authorization' => self::$authToken
            ]
        ]);

        $responseBody = json_decode($resp->getBody()->getContents(), true);



        self::$workflowId = $responseBody['id'];
    }    

}
