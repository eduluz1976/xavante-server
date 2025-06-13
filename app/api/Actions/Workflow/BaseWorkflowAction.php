<?php

namespace Xavante\API\Actions\Workflow;

use Xavante\API\Actions\BaseAction;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\DTO\BaseDTO;
use Xavante\API\Services\WorkflowService;

abstract class BaseWorkflowAction extends BaseAction
{
    protected WorkflowService $workflowService;

    public function __construct($app)
    {
        parent::__construct($app);
        $this->workflowService = $app->getContainer()->get(WorkflowService::class);
    }


    protected function getDTOFromRequest(array $data): BaseDTO
    {
        return new CreateWorkflowRequestDTO($data);
    }

}
