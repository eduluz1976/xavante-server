<?php

namespace Xavante\API\Actions\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Xavante\API\Actions\BaseAction;
use Xavante\API\Helpers\AuthHelper;

class RenewAuthTokenAction extends BaseAction
{
    use AuthHelper;

    public function __invoke(Request $request, Response $response, array $args = [])
    {
        $data = $this->getData($request);

        try {
            [$check, $token] = $this->generateAuthToken($data['client_id'], $data['secret']);
            $payloadResponse = [
                'status' => 'success',
                'message' => 'Auth credentials created',
                'X-ACCESS-TOKEN' => $token,
                'X-ACCESS-CHECK' => $check
            ];

            return $this->jsonResponse($response, $payloadResponse);

        } catch (\Exception $e) {
            return $this->jsonResponse($response, ['status' => 'error', 'message' => $e->getMessage()], 401);
        }

    }

    protected function generateAuthToken($clientId, $secret): array
    {
        $token = '';
        $check = '';

        $hashedSecret = hash('sha256', $secret);
        $intermediaryKey = $this->getIntermediaryKey($clientId, $hashedSecret);

        $payload = [
            'client_id' => $clientId,
            'timestamp' => time() // Current time in seconds since Unix epoch (UTC)
        ];

        // Convert payload to JSON string
        $jsonPayload = json_encode($payload);

        // Step 4: Sign the JSON string using HMAC with the intermediary key
        $check = hash_hmac('sha256', $jsonPayload, $intermediaryKey);

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
