<?php

namespace Xavante\API\Repositories;

use Xavante\API\Documents\BaseDocument;

interface RepositoryInterface
{

    /**
     * Find a document by its ID.
     *
     * @param string $id
     * @return mixed
     */
    public function findById(string $id, string $documentClassName): ?BaseDocument;

    /**
     * Save a document.
     *
     * @param mixed $document
     * @return mixed
     */
    public function save(BaseDocument $document): BaseDocument;

    /**
     * Delete a document by its ID.
     *
     * @param string $id
     * @return bool
     */
    public function deleteById(string $id, string $documentClassName): bool;

    /**
     * Get all documents.
     *
     * @return array
     */
    public function findAll(string $documentClassName, array $filters = []): array;
}
