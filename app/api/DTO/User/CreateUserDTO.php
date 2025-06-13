<?php

namespace Xavante\API\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;
use Xavante\API\DTO\BaseDTO;

class CreateUserDTO extends BaseDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 100)]
    public string $name;

    public string $type;
    public string $status;
    public array $permissions;


    public function __construct(array $data)
    {
        $this->name = trim($data['name']) ?? '';
        $this->status = trim($data['status']) ?? '';
        $this->type = trim($data['type']) ?? '';
        $this->permissions = $data['permissions'] ?? [];
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'status' => $this->status,
            'type' => $this->type,
            'permissions' => $this->permissions,
        ];
    }
}
