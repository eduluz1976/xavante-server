<?php

namespace Xavante\API\DTO\User;

use Xavante\API\DTO\BaseDTO;

class UserDTO extends BaseDTO
{
    public string $id;
    public string $name;
    public string $status;
    public string $hashed_secret;
    public string $client_id;
    public string $secret;
    public array $permissions;



    public function __construct(array $data = [])
    {
        $this->fromArray($data);
    }


    public function fromArray(array $data): self
    {
        $this->id = $data['id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->hashed_secret = $data['hashed_secret'] ?? null;
        $this->client_id = $data['client_id'] ?? '';
        $this->status = $data['status'] ?? 'draft';
        $this->permissions = $data['permissions'] ?? [];

        return $this;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'client_id' => $this->client_id,
            'secret' => $this->secret,
            'permissions' => $this->permissions
        ];
    }
}
