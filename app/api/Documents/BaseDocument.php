<?php

namespace Xavante\API\Documents;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

abstract class BaseDocument implements \JsonSerializable
{

    #[ODM\Id]
    public ?string $id;


    /**
     * Convert the document to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Convert the document to JSON.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __construct(?array $data = [])
    {
        $this->id = $data['_id'] ?? ($data['id'] ?? null);
    }
}