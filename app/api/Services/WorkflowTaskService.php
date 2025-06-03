<?php

namespace Xavante\API\Services;

use Xavante\API\DTO\Task\CreateWorkflowTaskRequestDTO;
use Xavante\API\DTO\Task\WorkflowTaskDTO;
use Xavante\API\Factories\WorkflowFactory;
use Xavante\API\Factories\WorkflowTaskFactory;


class WorkflowTaskService {


    public function __construct(
        private \Xavante\API\Repositories\RepositoryInterface $repository,
        private \Xavante\API\Services\WorkflowService $workflowService
    ) {
    }



    /**
     * Steps:
     * 1. Retrieve the workflow by ID from the WorkflowService.
     * 2. Create a new WorkflowTask document using the data from CreateWorkflowTaskRequestDTO.
     * 3. Embed the new WorkflowTask document into the workflow document.
     * 4. Save the updated workflow document using the repository.
     * 5. Return a WorkflowTaskDTO containing the details of the created task.
     */
    public function createWorkflowTask(CreateWorkflowTaskRequestDTO $createWorkflowTaskRequest): WorkflowTaskDTO
    {

        // Step 1: Retrieve the workflow by ID
        $workflowId = $createWorkflowTaskRequest->workflowId;
        $workflowDTO = $this->workflowService->getWorkflowById($workflowId);
        if (!$workflowDTO) {
            throw new \RuntimeException('Workflow not found');
        }

        // Step 2: Create a new WorkflowTask document
        $workflowTask = WorkflowTaskFactory::createDocumentFromRequestDTO($createWorkflowTaskRequest);

        // Step 3: Embed the new WorkflowTask document into the workflow document
        $workflow = $this->workflowService->getWorkflowById($workflowId);
        if (!$workflow) {
            throw new \RuntimeException('Workflow not found');
        }

        $workflowDocument = WorkflowFactory::createDocumentFromArray($workflow->jsonSerialize());

        // Assuming the Workflow document has a method to add tasks
        $workflowDocument->addTask($workflowTask);

      
        // Step 4: Save the updated workflow document using the repository
        $documentResult = $this->repository->save($workflowDocument);

        // Check if the document was saved successfully
        if (!$documentResult) {
            throw new \RuntimeException('Failed to create workflow');
        }

        // Return the created workflow document
        return new WorkflowTaskDTO($workflowTask->jsonSerialize());
    }

}