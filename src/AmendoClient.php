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
            $headers['X-API-KEY'] = $this->config->apiKey;
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

        return $this->httpClient->request($method, $this->config->baseUrl . $url, $options);
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
                url: '/ws/rest/jobstart/ticket',
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
                url: sprintf('/ws/rest/job/%d/overview', $jobId),
                headers: ['Accept' => 'application/json', 'Content-Type' => 'application/json'],
                rawBody: '{"query":"","variables":{}}'
            );

            return json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ": failed for Amendo job <$jobId>: " .
                $ex->getMessage(), $ex->getCode(), $ex);
        }
    }
}
