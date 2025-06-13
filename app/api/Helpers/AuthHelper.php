<?php

namespace Xavante\API\Helpers;

trait AuthHelper
{
    protected function getIntermediaryKey($clientId, $hashedSecret)
    {
        return $clientId . '-' . $hashedSecret;
    }


    protected function getCacheKey(string $clientId): string
    {
        return 'client_id='.$clientId;
    }


}
