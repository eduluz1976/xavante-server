<?php

namespace Xavante\API\Services;

class ConfigurationService
{
    protected array $data = [];


    public function get(string $key, mixed $default = null): mixed
    {

        $configValue = getenv($key);
        if ($configValue !== null) {
            return $configValue;
        }
        return $default;
    }

    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getData($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

}
