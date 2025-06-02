<?php

namespace Xavante\API\Actions\Workflow;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Xavante\API\Services\WorkflowService;
use Xavante\API\DTO\Workflow\UpdateWorkflowRequestDTO;



class UpdateWorkflowAction extends BaseWorkflowAction
{

    public function __construct(private WorkflowService $workflowService){}



    public function __invoke(Request $request, Response $response, array $args = [])
    {

        $workflowId = $args['id'] ?? null;

        if (!$workflowId) {
            return $this->jsonResponse($response, ['error' => 'Workflow ID is required'], 400);
        }

        // Extract the JSON body from the request
        $data = $this->getData($request);

        try {
            $requestDTO = $this->getDTOFromRequest($data);
            $this->validateRequestData($requestDTO);

            $workflowDTO = $this->workflowService->getWorkflowById($workflowId);
            if (!$workflowDTO) {
                return $this->jsonResponse($response, ['error' => 'Workflow not found'], 404);
            }
            $updatedWorkflow = $this->workflowService->updateWorkflow($workflowDTO, $requestDTO);


        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['error' => 'Failed to retrieve workflow: ' . $e->getMessage()], 404);
        }

        return $this->jsonResponse($response, (array) $updatedWorkflow, 200);
    }

    protected function getDTOFromRequest(array $data): UpdateWorkflowRequestDTO
    {
        return new UpdateWorkflowRequestDTO($data);
    }

}