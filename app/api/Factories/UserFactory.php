<?php

namespace Xavante\API\Factories;

use Xavante\API\Documents\User;
use Xavante\API\DTO\User\CreateUserDTO;

class UserFactory
{
    public static function createDocumentFromRequestDTO(CreateUserDTO $dto): User
    {
        return new User($dto->jsonSerialize());
    }


}
