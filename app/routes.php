<?php

use Doctrine\ODM\MongoDB\DocumentManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use \Xavante\API\Actions\Authenticate;
use \Xavante\API\Actions\Status;
use \Xavante\API\Actions\Workflow\CreateWorkflowAction;
use Xavante\API\Actions\Workflow\ListWorkflows;
use Xavante\API\Actions\Workflow\RetrieveWorkflow;
use Xavante\API\Repositories\Mongo;
use Xavante\API\Repositories\RepositoryInterface;
use Xavante\API\Services\WorkflowService;

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
     $documentManager = $app->getContainer()->get(DocumentManager::class);

     return (new ListWorkflows($documentManager))($request, $response, $args);
});

$app->get('/api/v1/workflow/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
     $documentManager = $app->getContainer()->get(DocumentManager::class);

     return (new RetrieveWorkflow($documentManager))($request, $response, $args);
});