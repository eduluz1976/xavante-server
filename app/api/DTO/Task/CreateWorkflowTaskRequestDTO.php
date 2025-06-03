<?php

namespace Xavante\API\DTO\Task;


use Symfony\Component\Validator\Constraints as Assert;
use Xavante\API\DTO\BaseDTO;

class CreateWorkflowTaskRequestDTO extends BaseDTO
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 10, max: 255)]
    public string $name;

    public ?string $description = null;

    public string $workflowId;

    public function __construct(array $data)
    {
        $this->name = trim($data['name']) ?? '';
        $this->description = trim($data['description']) ?? null;
        $this->workflowId = $data['workflowId'] ?? 'default-workflow-id';
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'workflowId' => $this->workflowId,
        ];
    }
}
