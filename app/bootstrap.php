<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
// use Systlets\API\Providers\QueueProvider;
// use Systlets\API\Services\JobService;
use Xavante\API\ConfigEnum;
use UMA\DIC\Container;
use Doctrine\ORM\ORMSetup;

$container = new Container(require __DIR__ . '/etc/db.php');
// $container->set(EntityManager::class, static function (Container $c): EntityManager {
//     /** @var array $settings */
//     $settings = $c->get('settings');

//     $conn = DriverManager::getConnection($settings['doctrine']['connection']);

//     $config = ORMSetup::createAttributeMetadataConfiguration(
//         paths: array($settings['doctrine']['metadata_dirs']),
//         isDevMode: true,
//     );

//     return new EntityManager($conn, $config);
// });

// $container->set(JobService::class, static function (Container $c) {
//     return new JobService($c->get(EntityManager::class));
// });


$client = new \Predis\Client([
    'scheme' => 'tcp',
    'host' => getenv(ConfigEnum::CONFIG_QUEUE_REDIS_HOST) ?? 'redis',
    'port' => 6379,
    'database' => 0,
    'read_write_timeout' => 0,
]);


// $adapter = new \Superbalist\PubSub\Redis\RedisPubSubAdapter($client);

// $container->set(QueueProvider::class, $adapter);


return $container;
