<?php

namespace Xavante\API\Actions\Task;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class CreateWorkflowTaskAction extends BaseWorkflowTask
{
    public function __invoke(Request $request, Response $response, array $args = [])
    {
      

        $data = $this->getData($request);

        $workflowId = $args['workflow_id'] ?? null;
        if (!$workflowId) {
            return $this->jsonResponse($response, ['error' => 'Workflow ID is required'], 400);
        }
        $data['workflowId'] = $workflowId;

        $requestDTO = $this->getCreateDTOFromRequest($data);

        try {
            $this->validateRequestData($requestDTO);
            $createdWorkflowTask = $this->workflowTaskService->createWorkflowTask($requestDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->jsonResponse($response, ['error' => 'Validation failed: ' . $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            return $this->jsonResponse($response, ['error' => 'Workflow creation failed: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['error' => 'Failed to create workflow: ' . $e->getMessage()], 500);
        }        

        return $this->jsonResponse($response, ['id' => $createdWorkflowTask->id], 201);
    }
}