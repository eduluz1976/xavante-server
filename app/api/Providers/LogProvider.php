<?php

namespace Xavante\API\Providers;

use Monolog\Logger;
use Monolog\Handler\GelfHandler;
use Gelf\Publisher;
use Gelf\Transport\UdpTransport;

// TODO: refactor to use opentelemetry
class LogProvider
{
    protected static $instance = null;
    protected $logger;


    public static function getInstance(): LogProvider
    {
        if (self::$instance === null) {
            self::$instance = new LogProvider();
        }

        return self::$instance;
    }



    private function __construct()
    {
        $this->init();
    }


    private function init($facility = 'webapp')
    {
        $transport = new UdpTransport('host.docker.internal', 12201);
        // Publisher to send messages
        $publisher = new Publisher();
        $publisher->addTransport($transport);

        // Create GELF Handler
        $gelfHandler = new GelfHandler($publisher, Logger::DEBUG);

        // Create Logger Instance
        $this->logger = new Logger($facility);
        $this->logger->pushHandler($gelfHandler);
    }

    public static function resetLogger(string $facility)
    {
        self::getInstance()->init($facility);
    }




    public function info($message, $context = [])
    {
        $this->logger->info($message, $context);
    }

    public function debug($message, $context = [])
    {
        $this->logger->debug($message, $context);
    }

    public function error($message, $context = [])
    {
        $this->logger->error($message, $context);
    }
}
