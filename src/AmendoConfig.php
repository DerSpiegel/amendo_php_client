<?php

namespace DerSpiegel\AmendoClient;

use BadFunctionCallException;
use Uri\Rfc3986\Uri;


readonly class AmendoConfig
{
    public const string HTTP_USER_AGENT_DEFAULT = 'der-spiegel/amendo-client (https://github.com/DerSpiegel/amendo_php_client)';


    public function __construct(
        // Base server URL, e.g. "https://amendo.example.com"
        public Uri $baseUrl,
        public string $httpUserAgent = self::HTTP_USER_AGENT_DEFAULT,
        #[\SensitiveParameter] public ?string $apiKey = null,
        public int $httpRequestTimeout = 60,
        public bool $verifySslCertificate = true
    ) {
    }


    /**
     * Sanitizes parameters before calling the constructor
     */
    public static function create(
        string $baseUrl,
        string $httpUserAgent = self::HTTP_USER_AGENT_DEFAULT,
        ?string $apiKey = null,
        int $httpRequestTimeout = 60,
        bool $verifySslCertificate = true
    ): static {
        if (trim($baseUrl) === '') {
            throw new BadFunctionCallException(sprintf('%s: Base URL is empty.', __METHOD__));
        }

        return new static(
            new Uri(trim($baseUrl)),
            trim($httpUserAgent),
            ($apiKey === null ? null : trim($apiKey)),
            $httpRequestTimeout,
            $verifySslCertificate
        );
    }
}
