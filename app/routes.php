<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Routing\RouteCollectorProxy;
use \Xavante\API\Actions\AuthenticateAction;
use \Xavante\API\Actions\StatusAction;
use \Xavante\API\Actions\Workflow\CreateWorkflowAction;
use Xavante\API\Actions\Workflow\RetrieveWorkflowAction;
use Xavante\API\Actions\Workflow\UpdateWorkflowAction;
use Xavante\API\Actions\Workflow\DeleteWorkflowAction;
use Xavante\API\Actions\Workflow\ListWorkflowsAction;
use Xavante\API\Repositories\RepositoryInterface;
use Xavante\API\Services\WorkflowService;
use Xavante\API\Services\WorkflowTaskService;
use Xavante\API\Services\AuthenticationService;
use Xavante\API\Actions\Task\CreateWorkflowTaskAction;
use Xavante\API\Middleware\AuthenticateMiddleware;
use Xavante\API\Services\ConfigurationService;

$configurationService = $app->getContainer()->get(ConfigurationService::class);
$repository = $app->getContainer()->get(RepositoryInterface::class);
$authenticationService = new AuthenticationService(repository: $repository, config: $configurationService);
$workflowService = new WorkflowService(repository: $repository);
$workflowTaskService = new WorkflowTaskService(repository:$repository, workflowService: $workflowService);

$app->post('/api/v1/auth', function (Request $request, Response $response, array $args = []) use ($app) {
     return (new AuthenticateAction($app))($request, $response, $args);
});


$app->get('/api/v1/status', function (Request $request, Response $response, array $args = []) use ($app) {
     return (new StatusAction($app))($request, $response, $args);
});


$app->group('/api/v1/workflow', function(RouteCollectorProxy $group) use ($app) {

     $group->get('', function (Request $request, Response $response, array $args = []) use ($app) {     
          return (new ListWorkflowsAction($app))($request, $response, $args);
     });

     $group->get('/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
          return (new RetrieveWorkflowAction($app))($request, $response, $args);
     });

     $group->post('', function (Request $request, Response $response, array $args = []) use ($app) {
          return (new CreateWorkflowAction($app))($request, $response, $args);
     });

     $group->put('/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
          return (new UpdateWorkflowAction($app))($request, $response, $args);
     });

     $group->delete('/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
          return (new DeleteWorkflowAction($app))($request, $response, $args);
     });

     $group->post('/{workflow_id}/task', function (Request $request, Response $response, array $args = []) use ($app) {
          return (new CreateWorkflowTaskAction($app))($request, $response, $args);
     });

})->add($app->getContainer()->get(AuthenticateMiddleware::class));


// $app->post('/api/v1/workflow', function (Request $request, Response $response, array $args = []) use ($app) {
//      return (new CreateWorkflowAction($app))($request, $response, $args);
// });

// $app->get('/api/v1/workflow', function (Request $request, Response $response, array $args = []) use ($app) {
     
//      return (new ListWorkflowsAction($app))($request, $response, $args);
// });

// $app->get('/api/v1/workflow/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
//      return (new RetrieveWorkflowAction($app))($request, $response, $args);
// });

// $app->put('/api/v1/workflow/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
//      return (new UpdateWorkflowAction($app))($request, $response, $args);
// });

// $app->delete('/api/v1/workflow/{id}', function (Request $request, Response $response, array $args = []) use ($app) {
//      return (new DeleteWorkflowAction($app))($request, $response, $args);
// });


// $app->post('/api/v1/workflow/{workflow_id}/task', function (Request $request, Response $response, array $args = []) use ($app) {
//      return (new CreateWorkflowTaskAction($app))($request, $response, $args);
// });

// container->set(AuthenticateMiddleware::class