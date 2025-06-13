<?php

namespace Xavante\API;

class ConfigEnum
{
    public const CONFIG_DB_HOST = 'DB_HOST';
    public const CONFIG_DB_PORT = 'DB_PORT';
    public const CONFIG_DB_DATABASE = 'DB_NAME';
    public const CONFIG_DB_USERNAME = 'DB_USERNAME';
    public const CONFIG_DB_PASSWORD = 'DB_PASSWORD';
    public const CONFIG_DB_ROOT_PASSWORD = 'DB_ROOT_PASSWORD';
    public const CONFIG_QUEUE_REDIS_HOST = 'QUEUE_REDIS_HOST';
    public const CONFIG_QUEUE_CHANNEL_NAME = 'QUEUE_CHANNEL_NAME';

    public const CONFIG_CACHE_REDIS_HOST = 'REDIS_HOST';
    public const CONFIG_CACHE_REDIS_PORT = 'REDIS_PORT';

    public const CONFIG_DB_URI = 'DB_URI';
    public const CONFIG_DB_DRIVER = 'DB_DRIVER';
}
