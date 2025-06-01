<?php

namespace Xavante\API\Services;

use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\Documents\Workflow;
use Xavante\API\DTO\Workflow\WorkflowDTO;
use Xavante\API\Factories\WorkflowFactory;

class WorkflowService {


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
}