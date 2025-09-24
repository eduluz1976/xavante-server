<?php

namespace Xavante\API\Services;

use Xavante\API\Documents\User;
use Xavante\API\DTO\Workflow\CreateWorkflowRequestDTO;
use Xavante\API\DTO\Workflow\UpdateWorkflowRequestDTO;
use Xavante\API\Documents\Workflow;
use Xavante\API\DTO\User\CreateUserDTO;
use Xavante\API\DTO\User\UserDTO;
use Xavante\API\DTO\Workflow\WorkflowDTO;
use Xavante\API\Factories\UserFactory;
use Xavante\API\Factories\WorkflowFactory;
use Ramsey\Uuid\Uuid;

class UserService
{
    public function __construct(
        private \Xavante\API\Repositories\RepositoryInterface $repository
    ) {
    }



    public function createUser(CreateUserDTO $createUserRequest): UserDTO
    {

        $user = UserFactory::createDocumentFromRequestDTO($createUserRequest);

        $user->client_id = $this->createClientID();
        $secret = $this->createSecret();
        $user->hashed_secret = $this->hashSecret($secret);

        $documentResult = $this->repository->save($user);

        if (!$documentResult) {
            throw new \RuntimeException('Failed to create workflow');
        }

        $userDTO = new UserDTO($documentResult->jsonSerialize());
        $userDTO->secret = $secret;

        return $userDTO;
    }


    protected function createClientID(): string
    {
        return Uuid::uuid7()->toString();
    }

    protected function createSecret(): string
    {
        return $this->createRandomString(64);
    }


    /**
     * I want to generate a random string with 64 characters, but only alphanumeric characters (a-z, A-Z, 0-9)
     */
    protected function createRandomString(int $length): string
    {
        $bytes = random_bytes(48);
        $hash = hash('sha512', $bytes);
        $b64Encoded = base64_encode($hash);
        return substr($b64Encoded, 0, $length);
    }

    protected function hashSecret($secret)
    {
        return hash('sha256', $secret);
    }



    public function getUserByClientId(string $clientId): ?UserDTO
    {
        $usersFound = $this->repository->findAll(User::class, ['client_id' => $clientId]);
        if ($usersFound) {
            $userDTO = new UserDTO($usersFound[0]->jsonSerialize());
            return $userDTO;
        }
        return null;
    }
}
