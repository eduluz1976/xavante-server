<?php

namespace Xavante\API\Services;

use Xavante\API\DTO\Auth\AuthPayloadDTO;
use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class AuthenticationService {

  public function __construct(
        private \Xavante\API\Repositories\RepositoryInterface $repository
    ) {
    }

    protected function validateTimestamp($timestamp) {

        // TODO: set as config
        $ttl = 43200; // 30 days

        $date = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        if ($date->getTimestamp() < ($timestamp+$ttl) ) {
            throw new \RuntimeException("Expired authentication token");
        }
    }

    public function validateAndReturnAuthCredentials(string $token, string $check) {
        $payloadData = $this->extractPayloadData($token);


        $this->validateTimestamp($payloadData['timestamp']);
        

        $userDocuments = $this->repository->findAll( \Xavante\API\Documents\User::class,['client_id'=>$payloadData['client_id']]);
        $userDocument = $userDocuments[0];


        $intermediateKey = $this->getIntermediaryKey($userDocument->client_id, $userDocument->hashed_secret);

        $jsonPayload = base64_decode($token);

        $expectedToken = hash_hmac('sha256', $jsonPayload, $intermediateKey);

        $isValidSignature = hash_equals($expectedToken, $check);

        if (!$isValidSignature) {
            throw new \RuntimeException("Invalid auth token and/or check");
        }

        return $this->createAuthToken($payloadData['client_id']);

    }

    protected function getIntermediaryKey($clientId, $hashedSecret) {
        return $clientId . '-' . $hashedSecret;
    }


    protected function createAuthToken(string $clientId) : string {

        // TODO: set as config
        $key = InMemory::base64Encoded(
            'hiG8DlOKvtih6AxlZn5XKImZ06yu8I3mkOzaJrEuW8yAv8Jnkw330uMt8AEqQ5LB'
        );

        // TODO: set these variables as config
        $token = (new JwtFacade())->issue(
            new Sha256(),
            $key,
            static fn (
                Builder $builder,
                DateTimeImmutable $issuedAt
            ): Builder => $builder
                ->issuedBy('https://dev.xavante.dev')
                ->permittedFor('https://systlets.com')
                ->identifiedBy($clientId)
                ->expiresAt($issuedAt->modify('+10 minutes'))
        );
        return $token->toString();
    }





    protected  function extractPayloadData(string $payload) : array {

        $decodedPayload = base64_decode($payload, true);
        $jsonPayload = json_decode($decodedPayload, true);

        return [
            'client_id' => $jsonPayload['client_id'],
            'timestamp' => $jsonPayload['timestamp'],
        ];
    }

}