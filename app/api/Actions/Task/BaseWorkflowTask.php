<?php

namespace Xavante\API\Actions\Task;

use Xavante\API\Actions\BaseAction;
use Xavante\API\Services\WorkflowService;
use Xavante\API\Services\WorkflowTaskService;
use Xavante\API\DTO\Task\CreateWorkflowTaskRequestDTO;
use Xavante\API\DTO\BaseDTO;

abstract class BaseWorkflowTask extends BaseAction
{
    protected WorkflowTaskService $workflowTaskService;
    protected WorkflowService $workflowService;

    public function __construct($app)
    {
        parent::__construct($app);
        // protected WorkflowTaskService $workflowTaskService, protected WorkflowService $workflowService
        $this->workflowTaskService = $app->getContainer()->get(WorkflowTaskService::class);
        $this->workflowService = $app->getContainer()->get(WorkflowService::class);

    }

    protected function getCreateDTOFromRequest(array $data): BaseDTO
    {
        return new CreateWorkflowTaskRequestDTO($data);
    }


}
