<?php

namespace DerSpiegel\AmendoClient;

use DerSpiegel\AmendoClient\JobTicket\JobTicket;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;


class AmendoClient
{
    const HTTP_HEADER_API_KEY = 'X-API-KEY';

    protected Client $httpClient;


    public function __construct(
        readonly AmendoConfig    $config,
        readonly LoggerInterface $logger
    )
    {
        $this->httpClient = $this->newHttpClient();
    }


    protected function newHttpClient(): Client
    {
        $stack = HandlerStack::create();

        $stack->push(
            Middleware::log(
                $this->logger,
                new MessageFormatter('Sent AmendoClient {method} request to {uri}. Amendo response headers: {res_headers}'),
                LogLevel::DEBUG
            )
        );

        return new Client(['handler' => $stack]);
    }


    public function request(
        string  $method,
        string  $url,
        array   $headers = [],
        ?string $rawBody = null
    ): ResponseInterface
    {
        $headers['User-Agent'] = $this->config->httpUserAgent;

        if (!empty($this->config->apiKey)) {
            $headers[self::HTTP_HEADER_API_KEY] = $this->config->apiKey;
        }

        $options = [
            RequestOptions::HEADERS => $headers,
            RequestOptions::TIMEOUT => $this->config->httpRequestTimeout,
            RequestOptions::VERIFY => $this->config->verifySslCertificate
        ];

        if (!empty($rawBody)) {
            $options[RequestOptions::BODY] = $rawBody;
        }

        $this->logger->debug(sprintf('Sending AmendoClient %s request to <%s>.', $method, $url));

        return $this->httpClient->request($method, $url, $options);
    }


    /**
     * Start job ticket.
     * @param JobTicket $ticket JobTicket instance to start.
     * @return int OneVison Workspace JobTicket ID or NULL on error.
     */
    public function startJobTicket(JobTicket $ticket): int
    {
        try {
            $response = $this->request(
                method: 'POST',
                url: $this->config->baseUrl . '/ws/rest/jobstart/ticket',
                rawBody: $ticket->toXml()
            );

            $jobId = intval((string)$response->getBody());

            $this->logger->info(
                "Created new Amendo job with job id <$jobId>",
                [
                    'method' => __METHOD__,
                    'jobId' => $jobId
                ]);

            return $jobId;
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ': failed: ' . $ex->getMessage(),
                $ex->getCode(), $ex);
        }
    }


    /**
     * Get job overview.
     * @param int $jobId OneVision Workspace job id.
     * @return mixed Result object.
     */
    public function getJobOverview(int $jobId): array
    {
        try {
            $response = $this->request(
                method: 'GET',
                url: $this->config->baseUrl . sprintf('/ws/rest/job/%d/overview', $jobId),
                headers: ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
                rawBody: '{"query":"","variables":{}}'
            );

            return json_decode((string)$response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ": failed for Amendo job <$jobId>: " .
                $ex->getMessage(), $ex->getCode(), $ex);
        }
    }


    /**
     * Download an image result file from Amendo
     */
    public function downloadFileToPath(string $url, string $targetPath): void
    {
        try {
            $httpResponse = $this->request('GET', $url);
            $this->writeResponseBodyToPath($httpResponse, $targetPath);
        } catch (Exception $e) {
            throw new AmendoClientException(
                sprintf('%s: Failed to download <%s>: %s', __METHOD__, $url, $e->getMessage()),
                $e->getCode(), $e);
        }
    }


    protected function writeResponseBodyToPath(ResponseInterface $httpResponse, string $targetPath): void
    {
        $fp = fopen($targetPath, 'wb');

        if ($fp === false) {
            throw new AmendoClientException(sprintf('%s: Failed to open <%s> for writing', __METHOD__,
                $targetPath));
        }

        $ok = true;

        while ($data = $httpResponse->getBody()->read(1024)) {
            $ok = fwrite($fp, $data);

            if ($ok === false) {
                break;
            }
        }

        fclose($fp);

        if (!$ok) {
            throw new AmendoClientException(sprintf('%s: Failed to write HTTP response to <%s>', __METHOD__,
                $targetPath));
        }
    }
}
