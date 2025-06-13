<?php

namespace Xavante\API\DTO\Workflow;

use Xavante\API\DTO\BaseDTO;

class WorkflowDTO extends BaseDTO
{
    public string $id;
    public string $name;
    public ?string $description = null;
    public string $ownerId;
    public \DateTime $createdAt;
    public \DateTime $updatedAt;
    public string $status;
    public array $tasks = [];



    public function __construct(array $data = [])
    {
        $this->fromArray($data);
    }


    public function fromArray(array $data): self
    {
        $this->id = $data['id'] ?? '';
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->ownerId = $data['ownerId'] ?? '';
        $this->createdAt = isset($data['createdAt']) ? new \DateTime($data['createdAt']) : new \DateTime();
        $this->updatedAt = isset($data['updatedAt']) ? new \DateTime($data['updatedAt']) : new \DateTime();
        $this->status = $data['status'] ?? 'draft';
        $this->tasks = $data['tasks'] ?? [];

        return $this;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'ownerId' => $this->ownerId,
            'createdAt' => $this->createdAt?->format('Y-m-d H:i:s'),
            'updatedAt' => $this->updatedAt?->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'tasks' => $this->tasks,
        ];
    }

}
