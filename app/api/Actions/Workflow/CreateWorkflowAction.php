<?php

namespace Xavante\API\Actions\Workflow;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Xavante\API\Services\AuthenticationService;
use Xavante\API\Services\WorkflowService;

class CreateWorkflowAction extends BaseWorkflowAction
{

    // public function __construct(protected WorkflowService $workflowService, protected AuthenticationService $authenticationService){}


    public function __invoke(Request $request, Response $response, array $args = [])
    {
        // Extract the JSON body from the request
        $data = $this->getData($request);

        $requestDTO = $this->getDTOFromRequest($data);

        try {
            $this->validateRequestData($requestDTO);
            $createdWorkflow = $this->workflowService->createWorkflow($requestDTO);
        } catch (\InvalidArgumentException $e) {
            return $this->jsonResponse($response, ['error' => 'Validation failed: ' . $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            return $this->jsonResponse($response, ['error' => 'Workflow creation failed: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['error' => 'Failed to create workflow: ' . $e->getMessage()], 500);
        }

        return $this->jsonResponse($response, ['id' => $createdWorkflow->id], 201);
    }

}