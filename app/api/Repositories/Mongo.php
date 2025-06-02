<?php

namespace Xavante\API\Repositories;

use Xavante\API\Documents\BaseDocument;



class Mongo implements RepositoryInterface
{
    public function __construct(protected \Doctrine\ODM\MongoDB\DocumentManager $documentManager) {}    

    public function findById(string $id, string $documentClassName) : ?BaseDocument
    {
        /**  @disregard P1009 Undefined type  */ 
        $id = new \MongoDB\BSON\ObjectId($id);
        $arr = $this->documentManager->getDocumentCollection($documentClassName)->findOne(['_id'=>$id]);
        return  new $documentClassName($arr);
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

    public function findAll(string $documentClassName, array $filters = []): array
    {
        $query = $this->documentManager->getDocumentCollection($documentClassName)->find($filters);

        $result = [];

        foreach ($query as $item) {
            $result[] = new $documentClassName($item);
        }

        return (array) $result;
    }

}