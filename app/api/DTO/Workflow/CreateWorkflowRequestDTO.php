<?php

namespace Xavante\API\DTO\Workflow;

use Symfony\Component\Validator\Constraints as Assert;
use Xavante\API\DTO\BaseDTO;

class CreateWorkflowRequestDTO extends BaseDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    public string $name;

    public ?string $description = null;

    public string $ownerId;

    public function __construct(array $data)
    {
        $this->name = trim($data['name']) ?? '';
        $this->description = trim($data['description']) ?? null;
        $this->ownerId = $data['ownerId'] ?? 'default-owner-id';
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'ownerId' => $this->ownerId,
        ];
    }
}
