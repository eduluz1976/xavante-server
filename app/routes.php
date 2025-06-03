<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use \Xavante\API\Actions\Authenticate;
use \Xavante\API\Actions\Status;
use \Xavante\API\Actions\Workflow\CreateWorkflowAction;
use Xavante\API\Actions\Workflow\RetrieveWorkflowAction;
use Xavante\API\Actions\Workflow\UpdateWorkflowAction;
use Xavante\API\Actions\Workflow\DeleteWorkflowAction;
use Xavante\API\Actions\Workflow\ListWorkflowsAction;
use Xavante\API\Repositories\RepositoryInterface;
use Xavante\API\Services\WorkflowService;
use Xavante\API\Services\WorkflowTaskService;
use Xavante\API\Actions\Task\CreateWorkflowTaskAction;


$app->post('/api/v1/auth', function (Request $request, Response $response, array $args = []) {
     return (new Authenticate())($request, $response, $args);
});


$app->get('/api/v1/status', function (Request $request, Response $response, array $args = []) {
     return (new Status())($request, $response, $args);
});


$app->post('/api/v1/workflow', function (Request $request, Response $response, array $args = []) use ($app) {
     $workflowService = new WorkflowService($app->getContainer()->get(RepositoryInterface::class));
     return (new CreateWorkflowAction($workflowService))($request, $response, $args);
});

$app->get('/api/v1/workflow', function (Request $request, Response $response, array $args = []) use ($app) {
     $workflowService = new WorkflowService($app->getContainer()->get(RepositoryInterface::class));
     
     return (new ListWorkflowsAction($workflowService))($request, $response, $args);
});

$app->get('/api/v1/workflow/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
     $workflowService = new WorkflowService($app->getContainer()->get(RepositoryInterface::class));

     return (new RetrieveWorkflowAction($workflowService))($request, $response, $args);
});

$app->put('/api/v1/workflow/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
     $workflowService = new WorkflowService($app->getContainer()->get(RepositoryInterface::class));

     return (new UpdateWorkflowAction($workflowService))($request, $response, $args);
});

$app->delete('/api/v1/workflow/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
     $workflowService = new WorkflowService($app->getContainer()->get(RepositoryInterface::class));

     return (new DeleteWorkflowAction($workflowService))($request, $response, $args);
});


$app->post('/api/v1/workflow/{workflow_id}/task', function (Request $request, Response $response, array $args = []) use ($app) {
     $workflowService = new WorkflowService($app->getContainer()->get(RepositoryInterface::class));
     $workflowTaskService = new WorkflowTaskService($app->getContainer()->get(RepositoryInterface::class), $workflowService);
     return (new CreateWorkflowTaskAction($workflowTaskService, $workflowService))($request, $response, $args);
});