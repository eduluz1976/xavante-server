<?php

namespace Xavante\API\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xavante\API\Actions\BaseAction;
use Xavante\API\Helpers\AuthHelper;
use Xavante\API\Repositories\RepositoryInterface;

class RenewAuthTokenAction extends BaseAction
{
    use AuthHelper;
    protected RepositoryInterface $repository;

    public function __construct($app)
    {
        $this->repository = $app->getContainer()->get(RepositoryInterface::class);
        parent::__construct($app);
    }

    public function __invoke(Request $request, Response $response, array $args = [])
    {
        $data = $this->getData($request);

        try {
            [$check, $token] = $this->generateAuthToken($data['client_id'], $data['secret']);
            $payloadResponse = [
                'X-ACCESS-TOKEN' => $token,
                'X-ACCESS-CHECK' => $check,
            ];

            return $this->jsonResponse($response, $payloadResponse);

        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['status' => 'error', 'message' => $e->getMessage()], 401);
        }

    }

    /**
     * Generates a secure authentication token pair (access token and verification check).
     * 
     * This method implements a secure token generation process using HMAC-SHA256 signing:
     * 
     * 1. **Secret Hashing**: The input secret is hashed using SHA256 to create a secure hash
     * 2. **Intermediary Key Creation**: Combines client_id with the hashed secret to create a unique signing key
     * 3. **Payload Creation**: Builds a JSON payload containing:
     *    - client_id: The client identifier
     *    - timestamp: Current Unix timestamp (UTC) for token freshness validation
     * 4. **HMAC Signing**: Signs the JSON payload using HMAC-SHA256 with the intermediary key
     *    - This creates a tamper-proof verification check that can validate token integrity
     * 5. **Token Encoding**: Base64 encodes the JSON payload to create the access token
     * 
     * The returned array contains:
     * - [0] check: HMAC-SHA256 signature for verifying token authenticity and integrity
     * - [1] token: Base64-encoded payload containing client_id and timestamp
     * 
     * Security features:
     * - Prevents token tampering (any modification invalidates the HMAC check)
     * - Includes timestamp for expiration validation
     * - Uses client-specific signing keys to prevent cross-client token reuse
     * - SHA256 hashing protects the original secret from exposure
     * 
     * @param string $clientId The client identifier
     * @param string $secret The client's secret key
     * @return array [check, token] - HMAC signature and base64-encoded token
     */
    protected function generateAuthToken($clientId, $secret): array
    {
        $token = '';
        $check = '';

        $hashedSecret = $this->getHashedSecret($secret);
        $intermediaryKey = $this->getIntermediaryKey($clientId, $hashedSecret);

        // TODO: ensure the timestamp is in UTC
        $payload = [
            'client_id' => $clientId,
            'timestamp' => time() // Current time in seconds since Unix epoch (UTC)
        ];

        // Convert payload to JSON string
        $jsonPayload = json_encode($payload); // here $jsonPayload is string

        // Step 4: Sign the JSON string using HMAC with the intermediary key
        $check = $this->signJsonPayload($jsonPayload, $intermediaryKey);

        // Step 5: Return the signed string
        $token = base64_encode($jsonPayload);

        return [$check, $token];
    }


    protected function getData(Request $request): array
    {
        $data = json_decode($request->getBody()->getContents(), true);

        if (!isset($data['secret']) || !isset($data['client_id'])) {
            throw new \RuntimeException("secret and client_id are required");
        }

        return [
            'secret' => $data['secret'] ?? '',
            'client_id' => $data['client_id'] ?? '',
        ];
    }

}
