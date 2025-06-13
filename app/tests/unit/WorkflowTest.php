<?php

namespace tests\unit;

use Xavante\API\DTO\Workflow\CreateWorkflowRequest;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\DTO\Workflow\ItemWorkflow;
use Xavante\API\DTO\Workflow\WorkflowDTO;

class WorkflowTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateWorkflow()
    {
        $data = [
            'name' => 'Test Workflow',
            'description' => 'This is a test workflow.',
            'ownerId' => 'owner123'
        ];

        $workflow = new CreateWorkflowRequestDTO($data);

        $this->assertEquals($data['name'], $workflow->name);
        $this->assertEquals($data['description'], $workflow->description);
        $this->assertEquals($data['ownerId'], $workflow->ownerId);
    }

    public function testItemWorkflowSerialization()
    {
        $input = [
            'id' => '1',
            'name' => 'Sample Workflow',
            'description' => 'A sample workflow for testing.',
            'ownerId' => 'owner123',
            'createdAt' => '2023-10-01 12:00:00',
            'updatedAt' => '2023-10-02 12:00:00',
            'status' => 'active'
        ];

        $itemWorkflow = new WorkflowDTO();
        $itemWorkflow->fromArray($input);

        $json = json_encode($itemWorkflow);
        $this->assertJson($json);
        $this->assertStringContainsString('"id":"1"', $json);
        $this->assertStringContainsString('"name":"Sample Workflow"', $json);
    }
}
