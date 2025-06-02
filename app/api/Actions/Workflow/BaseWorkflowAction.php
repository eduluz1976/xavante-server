<?php

namespace Xavante\API\Actions\Workflow;

use \Xavante\API\Actions\BaseAction;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\DTO\BaseDTO;



abstract class BaseWorkflowAction extends BaseAction
{
  
    protected function getData(Request $request): array
    {
        $data = json_decode($request->getBody()->getContents(), true);
        return $data ?: [];
    }

    protected function getDTOFromRequest(array $data): BaseDTO
    {
        return new CreateWorkflowRequestDTO($data);
    }

    protected function validateRequestData(BaseDTO $requestDTO): void
    {
        $errors = [];

        if (($errors = $this->validateRequest($requestDTO)) !== false) {
            throw new \InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }
    }

}

