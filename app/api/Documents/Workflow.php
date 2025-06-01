<?php

namespace Xavante\API\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;

#[ODM\Document (collection: 'workflows')]
class Workflow extends BaseDocument {

    #[ODM\Id]
    public string $id;
    #[ODM\Field(type: 'string', nullable: false)]    
    public string $name;
    #[ODM\Field(type: 'date', options: ['default' => 'CURRENT_TIMESTAMP'])]
    public \DateTime $createdAt;
    #[ODM\Field(type: 'date', options: ['default' => 'CURRENT_TIMESTAMP'])]
    public \DateTime $updatedAt;
    #[ODM\Field(type: 'string', options: ['default' => 'draft'])]
    public string $status;
    #[ODM\Field(type: 'string', nullable: true)]    
    public string $description;
    #[ODM\Field(type: 'string', nullable: false)]
    public string $ownerId;

    public function __construct(array $data = []) {
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->ownerId = $data['ownerId'] ?? '';
    }
}