<?php


namespace Xavante\API\Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;


#[ODM\Document (collection: 'users')]
class User extends BaseDocument {

    #[ODM\Field(type: 'string', nullable: false)]    
    public string $client_id;

    #[ODM\Field(type: 'string', nullable: false)]    
    public string $hashed_secret;

    #[ODM\Field(type: 'string', nullable: true)]    
    public string $status;


    public function __construct(?array $data = []) {

        if ($data === null) {
            return;
        }
        parent::__construct($data);
        $this->client_id = $data['client_id'] ?? '';
        $this->hashed_secret = $data['hashed_secret'] ?? '';
        $this->status = $data['status'] ?? 'inactive';
    }


}