<?php

namespace Xavante\API\Actions\Workflow;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Xavante\API\Services\WorkflowService;


class RetrieveWorkflowAction extends BaseWorkflowAction
{

    public function __construct(private WorkflowService $workflowService){}



    public function __invoke(Request $request, Response $response, array $args = [])
    {

        $workflowId = $args['id'] ?? null;

        if (!$workflowId) {
            return $this->jsonResponse($response, ['error' => 'Workflow ID is required'], 400);
        }


        try {
            $workflow = $this->workflowService->getWorkflowById($workflowId);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['error' => 'Failed to retrieve workflow: ' . $e->getMessage()], 404);
        }

        return $this->jsonResponse($response, (array) $workflow, 200);
    }
}