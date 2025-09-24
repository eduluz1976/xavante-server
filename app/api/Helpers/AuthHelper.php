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


    protected function getHashedSecret($secret): string
    {
        return hash('sha256', $secret);
    }

    protected function signJsonPayload(string $jsonPayload, string $intermediaryKey): string
    {
        return hash_hmac('sha256', $jsonPayload, $intermediaryKey);
    }


}
