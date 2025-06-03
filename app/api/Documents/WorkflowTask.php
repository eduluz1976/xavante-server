<?php

namespace Xavante\API\Documents;

class WorkflowTask extends BaseDocument {


    public string $name;
    public string $description;
    public string $type;

    public function __construct(array $data = []) {
        parent::__construct($data);
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? null;
        $this->type = $data['type'] ?? 'default';
    }
}