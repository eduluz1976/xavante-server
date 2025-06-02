<?php

namespace Xavante\API\Actions\Workflow;

use \Xavante\API\Actions\Base;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;



abstract class BaseWorkflowAction extends Base
{
  
    protected function getData(Request $request): array
    {
        $data = json_decode($request->getBody()->getContents(), true);
        return $data ?: [];
    }

    protected function getDTOFromRequest(array $data): CreateWorkflowRequestDTO
    {
        return new CreateWorkflowRequestDTO($data);
    }

    protected function validateRequestData(CreateWorkflowRequestDTO $requestDTO): void
    {
        $errors = [];

        if (($errors = $this->validateRequest($requestDTO)) !== false) {
            throw new \InvalidArgumentException('Validation failed: ' . implode(', ', $errors));
        }
    }

}

