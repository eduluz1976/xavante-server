<?php

namespace Xavante\API\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'users')]
class User extends BaseDocument
{
    #[ODM\Field(type: 'string', nullable: false)]
    public string $client_id;

    #[ODM\Field(type: 'string', nullable: false)]
    public string $name;

    #[ODM\Field(type: 'string', nullable: false)]
    public string $hashed_secret;

    #[ODM\Field(type: 'string', nullable: true)]
    public string $status;

    #[ODM\Field(type: 'string', nullable: true)]
    public string $type;

    #[ODM\Field(type: 'hash', nullable: true)]
    public array $permissions;


    public function __construct(?array $data = [])
    {

        if ($data === null) {
            return;
        }

        parent::__construct($data);
        $this->name = $data['name'] ?? '';
        $this->client_id = $data['client_id'] ?? '';
        $this->hashed_secret = $data['hashed_secret'] ?? '';
        $this->status = $data['status'] ?? 'inactive';
        $this->type = $data['type'] ?? 'public';
        $this->permissions = $data['permissions'] ?? [];
    }


}
