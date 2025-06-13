<?php

namespace Xavante\API\DTO\Auth;

class AuthPayloadDTO
{
    public string $clientId;

    public string $timestamp;

    public string $jwtToken;



    public function __construct(array $data = [])
    {
        $this->fromArray($data);
    }


    public function fromArray(array $data): self
    {
        $this->clientId = $data['client_id'] ?? '';
        $this->timestamp = $data['timestamp'] ?? '';
        $this->jwtToken = $data['jwt_token'] ?? '';
        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'timestamp' => $this->timestamp,
            'client_id' => $this->clientId,
            'jwt_token' => $this->jwtToken,
        ];
    }
}
