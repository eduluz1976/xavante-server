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
        $user->hashed_secret = $this->hashSecret($user->client_id, $secret);

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
        $seed = random_bytes(50);
        $b64Encoded = base64_encode($seed);
        return substr($b64Encoded, -48);
    }

    protected function createSecret(): string
    {
        $seed = random_bytes(150);
        $b64Encoded = base64_encode($seed);
        return substr($b64Encoded, -128);
    }

    protected function hashSecret($clientId, $secret)
    {
        return hash('sha256', $secret);
    }



    public function getUserByClientId(string $clientId): ?UserDTO
    {
        $usersFound = $this->repository->findAll(User::class, ['client_id' => $clientId]);
        if ($usersFound) {
            // return $usersFound[0];
            $userDTO = new UserDTO($usersFound[0]->jsonSerialize());
            return $userDTO;
        }
        return null;
    }
}
