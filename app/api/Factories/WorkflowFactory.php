<?php

namespace Xavante\API\Factories;

use Xavante\API\Documents\Workflow;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\DTO\Workflow\UpdateWorkflowRequestDTO;
use \Xavante\API\Documents\Workflow as WorkflowDocument;
use Xavante\API\DTO\Workflow\WorkflowDTO;


class WorkflowFactory {
    /**
     * Create a new workflow from the given data.
     *
     * @param array $data
     * @return \Xavante\API\Documents\Workflow
     */
    public static function createDocumentFromRequestDTO(CreateWorkflowRequestDTO $dto): WorkflowDocument
    {
        return new WorkflowDocument($dto->jsonSerialize());
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