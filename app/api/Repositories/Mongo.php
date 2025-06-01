<?php

namespace Xavante\API\Repositories;

use Xavante\API\Documents\BaseDocument;



class Mongo implements RepositoryInterface
{
    public function __construct(protected \Doctrine\ODM\MongoDB\DocumentManager $documentManager) {}    

    public function findById(string $id, string $documentClassName) : BaseDocument
    {        
        return  $this->documentManager->getDocumentCollection($documentClassName)->findOne(['_id' => $id]);
    }

    public function save(BaseDocument $document): BaseDocument
    {
            $this->documentManager->persist($document);
            $this->documentManager->flush();    
        return $document;
    }

    public function deleteById(string $id, string $documentClassName): bool
    {
        $result = $this->documentManager->getDocumentCollection($documentClassName)->deleteOne(['_id' => $id]);
        return $result->getDeletedCount() > 0;
    }

    public function getAll(string $documentClassName): array
    {
        return (array) $this->documentManager->getDocumentCollection($documentClassName)->find();
    }

}