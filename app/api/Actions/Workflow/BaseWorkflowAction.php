<?php

namespace Xavante\API\Actions\Workflow;

use \Xavante\API\Actions\BaseAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\DTO\BaseDTO;



abstract class BaseWorkflowAction extends BaseAction
{
    protected function getDTOFromRequest(array $data): BaseDTO
    {
        return new CreateWorkflowRequestDTO($data);
    }


}

