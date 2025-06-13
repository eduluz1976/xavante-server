<?php

namespace Xavante\API\Repositories;

use Predis\Client;

class Redis
{
    public Client $client;

    public function __construct(string $scheme = 'tcp', string $host = 'redis', int $port = 6379)
    {
        $this->client = new \Predis\Client([
            'scheme' => $scheme,
            'host' => $host,
            'port' => $port
        ]);
    }

    public function getSingleValue($key)
    {
        return $this->client->get($key) ?? null;
    }

    public function setSingleValue($key, $value)
    {
        $this->client->set($key, $value);
    }


}
