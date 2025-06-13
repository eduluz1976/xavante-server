<?php

namespace Xavante\API\Services;

use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\DTO\Workflow\UpdateWorkflowRequestDTO;
use Xavante\API\Documents\Workflow;
use Xavante\API\DTO\Workflow\WorkflowDTO;
use Xavante\API\Factories\WorkflowFactory;

class WorkflowService
{
    public function __construct(
        private \Xavante\API\Repositories\RepositoryInterface $repository
    ) {
    }



    public function createWorkflow(CreateWorkflowRequestDTO $createWorkflowRequest): WorkflowDTO
    {

        $workflow = WorkflowFactory::createDocumentFromRequestDTO($createWorkflowRequest);

        $documentResult = $this->repository->save($workflow);

        if (!$documentResult) {
            throw new \RuntimeException('Failed to create workflow');
        }

        return new WorkflowDTO($documentResult->jsonSerialize());
    }

    public function getWorkflowById(string $id): WorkflowDTO
    {
        $workflow = $this->repository->findById($id, Workflow::class);

        if (!$workflow) {
            throw new \RuntimeException('Workflow not found');
        }


        return new WorkflowDTO($workflow->jsonSerialize());
    }

    public function deleteWorkflow(string $id): WorkflowDTO
    {
        $workflow = $this->repository->findById($id, Workflow::class);

        if (!$workflow) {
            throw new \RuntimeException('Workflow not found');
        }

        $this->repository->deleteById($id, Workflow::class);

        return new WorkflowDTO($workflow->jsonSerialize());
    }

    public function updateWorkflow(WorkflowDTO $existingWorkflow, UpdateWorkflowRequestDTO $updateRequest): WorkflowDTO
    {
        // Update the existing workflow with new data
        $updatedWorkflow = WorkflowFactory::updateDocumentFromRequestDTO($existingWorkflow, $updateRequest);

        // Save the updated workflow
        $documentResult = $this->repository->save($updatedWorkflow);

        if (!$documentResult) {
            throw new \RuntimeException('Failed to update workflow');
        }

        return new WorkflowDTO($documentResult->jsonSerialize());
    }

    public function findAllWorkflows(array $filters = []): array
    {
        $workflows = $this->repository->findAll(Workflow::class, $filters);

        if (!$workflows) {
            return [];
        }

        return array_map(function ($workflow) {
            return new WorkflowDTO($workflow->jsonSerialize());
        }, $workflows);
    }
}
