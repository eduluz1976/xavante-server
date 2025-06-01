<?php

use Xavante\API\ConfigEnum;
use DI\ContainerBuilder;
use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoDB\Client;
use Psr\Container\ContainerInterface;
use Xavante\API\Repositories\RepositoryInterface;
use \Xavante\API\Repositories\Mongo;
use \Predis\Client as PredisClient;

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
    },

    
    PredisClient::class, static function (ContainerInterface $container): PredisClient {
        return new PredisClient([
            'scheme' => 'tcp',
            'host' => getenv(ConfigEnum::CONFIG_QUEUE_REDIS_HOST) ?? 'redis',
            'port' => 6379,
            'database' => 0,
            'read_write_timeout' => 0,
        ]);
    },
    
);

return $container;
