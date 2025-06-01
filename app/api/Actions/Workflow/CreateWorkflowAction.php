<?php

namespace Xavante\API\Actions\Workflow;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Xavante\API\Actions\Base;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\Services\WorkflowService;

class CreateWorkflowAction extends Base
{

    public function __construct(private WorkflowService $workflowService){}


    public function __invoke(Request $request, Response $response, array $args = [])
    {
        // Extract the JSON body from the request
        $data = json_decode($request->getBody()->getContents(), true);

        $requestDTO = new CreateWorkflowRequestDTO($data);

        if (($errors = $this->validateRequest($requestDTO)) !== false) {
            return $this->jsonResponse($response, ['error' => 'Validation failed', 'details' => $errors], 400);
        }

        try {
            $createdWorkflow = $this->workflowService->createWorkflow($requestDTO);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['error' => 'Failed to create workflow: ' . $e->getMessage()], 500);
        }

        return $this->jsonResponse($response, ['id' => $createdWorkflow->id], 201);
    }
}