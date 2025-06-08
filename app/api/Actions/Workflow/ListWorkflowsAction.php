<?php

namespace Xavante\API\Actions\Workflow;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Xavante\API\Actions\Workflow\BaseWorkflowAction;
use Xavante\API\DTO\Workflow\WorkflowDTO;

class ListWorkflowsAction extends BaseWorkflowAction
{


    public function __invoke(Request $request, Response $response, array $args = [])
    {

        $filters = [];

        try {
            $workflows = $this->workflowService->findAllWorkflows($filters);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['error' => 'Failed to create workflow: ' . $e->getMessage()], 500);
        }

        $rows = [];

        foreach ($workflows as $workflow) {
            $rows[] = (new WorkflowDTO($workflow->jsonSerialize()))->jsonSerialize();
        }

        $data = [
            'count' => count($rows),
            'rows'=> $rows,            
        ];

        return $this->jsonResponse($response, $data, 200);
    }
}