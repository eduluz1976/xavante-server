<?php

namespace Xavante\API\Factories;

use Xavante\API\DTO\Workflow\UpdateWorkflowRequestDTO;
use Xavante\API\DTO\Task\CreateWorkflowTaskRequestDTO;
use Xavante\API\Documents\WorkflowTask as WorkflowTaskDocument;
use \Xavante\API\Documents\Workflow as WorkflowDocument;
use Xavante\API\DTO\Workflow\WorkflowDTO;
use Kodus\Helpers\UUID;


class WorkflowTaskFactory {
    /**
     * Create a new workflow task from the given data.
     *
     * @param array $data
     * @return \Xavante\API\Documents\WorkflowTask
     */
    public static function createDocumentFromRequestDTO(CreateWorkflowTaskRequestDTO $dto): WorkflowTaskDocument
    {
        $dto = new WorkflowTaskDocument($dto->jsonSerialize());
        

        // generate uuid from php native function
        $dto->id = UUID::create();
        return $dto;
    }

    /**
     * Create a workflow DTO from an array.
     *
     * @param array $data
     * @return \Xavante\API\DTO\Workflow\WorkflowDTO
     */
    public static function createDocumentFromArray(array $data): WorkflowDocument
    {
        return new WorkflowDocument($data);
    }

    public static function updateDocumentFromRequestDTO(WorkflowDTO $existingWorkflow, UpdateWorkflowRequestDTO $updateRequest): WorkflowDocument
    {
        // Update the existing workflow with new data
        $existingWorkflow->name = $updateRequest->name;
        $existingWorkflow->description = $updateRequest->description;
        $existingWorkflow->ownerId = $updateRequest->ownerId;
        $existingWorkflow->status = $updateRequest->status;
        $existingWorkflow->updatedAt = new \DateTime();

        return new WorkflowDocument($existingWorkflow->jsonSerialize());
    }
}