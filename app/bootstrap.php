<?php

use Xavante\API\ConfigEnum;
use DI\ContainerBuilder;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use GuzzleHttp\Psr7\Response;
use MongoDB\Client;
use Psr\Container\ContainerInterface;
use Xavante\API\Repositories\RepositoryInterface;
use \Xavante\API\Repositories\Mongo;
use \Predis\Client as PredisClient;
use Psr\Http\Message\ResponseFactoryInterface;
use Xavante\API\Middleware\AuthenticateMiddleware;
use Xavante\API\Services\AuthenticationService;
use Xavante\API\Services\ConfigurationService;
use Xavante\API\Services\WorkflowService;
use Slim\Psr7\Factory\ResponseFactory;


$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/etc/db.php');

$containerBuilder->addDefinitions([
    RepositoryInterface::class => \DI\autowire(Mongo::class)
        ->constructorParameter('documentManager', \DI\get(DocumentManager::class)),
]); 


$container = $containerBuilder->build();


$container->set(
    DocumentManager::class, static function (ContainerInterface $container): DocumentManager {

        $settings = $container->get('settings')['doctrine']['connection'];


        $client = new Client($settings['uri'], ['username' => $settings['username'], 'password' => $settings['password']]);
        $config = new Configuration();

        $config->setDefaultDB($settings['dbname']);

        $config->setProxyDir(__DIR__ . '/api/Proxies');
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir(__DIR__ . '/api/Hydrators');
        $config->setHydratorNamespace('Hydrators');


        $documentManager = DocumentManager::create($client, $config);

        $driver = new \Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver(
            paths: [__DIR__ . '/api/Documents']
        );
        $documentManager->getConfiguration()->setMetadataDriverImpl($driver);

        return $documentManager;
    }
    
);


$container->set(ConfigurationService::class, static function(ContainerInterface $container): ConfigurationService {
        return new ConfigurationService();
    });


$container->set(WorkflowService::class, static function(ContainerInterface $container): WorkflowService {
        $repository = $container->get(RepositoryInterface::class);
        return new WorkflowService($repository);
    });


$container->set(AuthenticationService::class, static function(ContainerInterface $container): AuthenticationService {
        $repository = $container->get(RepositoryInterface::class);
        $config = $container->get(ConfigurationService::class);
        return new AuthenticationService(repository: $repository, config: $config);
    });

    





$container->set(ResponseFactoryInterface::class, static function(ContainerInterface $container): ResponseFactoryInterface {
        // return $container->get(ResponseFactory)
        return $container->get(ResponseFactory::class);
    });


$container->set(AuthenticateMiddleware::class, static function(ContainerInterface $container): AuthenticateMiddleware {
        return new AuthenticateMiddleware($container);
    });


$container->set(PredisClient::class, static function (ContainerInterface $container): PredisClient {
        return new PredisClient([
            'scheme' => 'tcp',
            'host' => getenv(ConfigEnum::CONFIG_QUEUE_REDIS_HOST) ?? 'redis',
            'port' => 6379,
            'database' => 0,
            'read_write_timeout' => 0,
        ]);
    });

return $container;
