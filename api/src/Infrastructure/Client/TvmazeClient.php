<?php

declare(strict_types=1);

namespace App\Infrastructure\Client;

use App\Infrastructure\Exception\ApiRequestParseException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TvmazeClient extends AbstractApiClient implements ApiInterface
{
    private const string BASE_URL = 'https://api.tvmaze.com';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function performRequest(string $query, string $request): array
    {
        $query = strtolower(trim($query));

        $requestStrPos = strpos($request, '_');

        if (false === $requestStrPos) {
            throw new ApiRequestParseException($request);
        }

        $requestType = strtolower(substr($request, 0, $requestStrPos));

        $httpMethod = match ($requestType) {
            'get' => Request::METHOD_GET,
            'post' => Request::METHOD_POST,
            'put' => Request::METHOD_PUT,
            'delete' => Request::METHOD_DELETE,
            default => throw new \InvalidArgumentException("Invalid request type: {$requestType}"),
        };

        try {
            return $this->makeRequestWithRetry(
                $httpMethod,
                sprintf($request, $query),
            );
        } catch (\Exception) {
            throw new \RuntimeException('Failed to make request', 0);
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function makeRequest(string $httpMethod, string $endpoint): array
    {
        $response = $this->httpClient->request(
            $httpMethod,
            self::BASE_URL.$endpoint,
            [
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ],
        );

        return $response->toArray();
    }

    /**
     * @return array<string, mixed>
     */
    protected function makeRequestWithRetry(
        string $method,
        string $endpoint,
        int $maxRetries = 3,
    ): array {
        $retryCount = 0;

        while ($retryCount < $maxRetries) {
            try {
                return $this->makeRequest($method, $endpoint);
            } catch (\Exception $e) {
                ++$retryCount;
            }
        }

        throw new \Exception("Request failed after {$maxRetries} retries");
    }
}
