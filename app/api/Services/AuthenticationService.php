<?php

namespace Xavante\API\Services;

use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\JwtFacade;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\JWT\Validation\Constraint;
use RuntimeException;
use Xavante\API\Helpers\AuthHelper;
use Xavante\API\Repositories\Redis;
use Xavante\API\Services\ConfigurationService;
use Xavante\API\Repositories\RepositoryInterface;

class AuthenticationService
{
    use AuthHelper;


    public function __construct(
        protected RepositoryInterface $repository,
        protected ConfigurationService $config,
        protected Redis $redis,
        protected UserService $userService,
    ) {
    }

    public function validateAndReturnAuthCredentials(string $token, string $check)
    {
        $payloadData = $this->extractPayloadData($token);


        $this->validateTimestamp($payloadData['timestamp']);

        $userDocuments = $this->repository->findAll(\Xavante\API\Documents\User::class, ['client_id' => $payloadData['client_id']]);
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


    public function verifyAuthToken(string $token)
    {
        $key = InMemory::base64Encoded(
            $this->config->get('AUTH_SERVER_SECRET_KEY')
        );


        $authObj = (new JwtFacade())->parse(
            $token,
            new Constraint\SignedWith(new Sha256(), $key),
            new Constraint\StrictValidAt(
                new FrozenClock(new DateTimeImmutable('now', new \DateTimeZone('UTC')))
            )
        );

        $authData = $authObj->claims()->all();

        $clientId = $authData['jti'];
        $cacheKey = $this->getCacheKey($clientId);
        $userData = $this->redis->client->get($cacheKey);
        if (!$userData) {
            $user = $this->userService->getUserByClientId($clientId);
            if (!$user) {
                return throw new RuntimeException('Invalid user');
            }
            $userData = [
                'name' => $user->name
                ,'permissions' => $user->permissions
            ];

            $userData = json_encode($userData, true);

            $this->redis->client->set($cacheKey, $userData);
            $this->redis->client->expire($cacheKey, 60);
        }

        $userData = json_decode($userData, true);

        $this->config->setData('CLIENT_NAME', $userData['name']);
        $this->config->setData('CLIENT_ID', $clientId);
        $this->config->setData('CLIENT_PERMISSIONS', $userData['permissions'] ?? []);
    }


    protected function createAuthToken(string $clientId): string
    {


        $key = InMemory::base64Encoded(
            $this->config->get('AUTH_SERVER_SECRET_KEY')
        );

        $config = $this->config;



        $token = (new JwtFacade())->issue(
            new Sha256(),
            $key,
            static fn (
                Builder $builder,
                DateTimeImmutable $issuedAt
            ): Builder => $builder
                ->issuedBy($config->get('AUTH_TOKEN_ISSUED_BY'))
                ->permittedFor($config->get('AUTH_TOKEN_PERMITTED_FOR'))
                ->identifiedBy($clientId)
                ->expiresAt($issuedAt->modify($config->get('AUTH_TOKEN_EXPIRES_AT')))
        );
        return $token->toString();
    }





    protected function extractPayloadData(string $payload): array
    {

        $decodedPayload = base64_decode($payload, true);
        $jsonPayload = json_decode($decodedPayload, true);

        if (empty($jsonPayload) || !isset($jsonPayload['client_id']) || !isset($jsonPayload['timestamp'])) {
            print_r([
                'payload' => $payload,
                'decodedPayload' => $decodedPayload,
                'jsonPayload' => $jsonPayload
            ]);
            exit;
        }

        return [
            'client_id' => $jsonPayload['client_id'],
            'timestamp' => $jsonPayload['timestamp'],
        ];
    }


    protected function validateTimestamp($timestamp)
    {

        $ttl = $this->config->get('AUTH_SESSION_TTL', 43200);

        $date = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

        if ($date->getTimestamp() < ($timestamp + $ttl)) {
            throw new \RuntimeException("Expired authentication token");
        }
    }

    protected function getCacheKey(string $clientId): string
    {
        return 'client_id='.$clientId;
    }

}
