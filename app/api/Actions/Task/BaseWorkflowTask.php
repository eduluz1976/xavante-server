<?php

namespace Xavante\API\Actions\Task;

use \Xavante\API\Actions\BaseAction;
use Xavante\API\Services\WorkflowService;
use Xavante\API\Services\WorkflowTaskService;
use Xavante\API\DTO\Task\CreateWorkflowTaskRequestDTO;
use Xavante\API\DTO\BaseDTO;

abstract class BaseWorkflowTask extends BaseAction
{

    public function __construct(protected WorkflowTaskService $workflowTaskService, protected WorkflowService $workflowService) {}

    protected function getCreateDTOFromRequest(array $data): BaseDTO
    {
        return new CreateWorkflowTaskRequestDTO($data);
    }


}