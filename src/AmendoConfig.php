<?php

namespace DerSpiegel\AmendoClient;

use Uri\Rfc3986\Uri;


class AmendoConfig
{
    public function __construct(
        // Base server URL, e.g. "https://amendo.example.com"
        public readonly Uri $baseUrl,
        public string $httpUserAgent = 'der-spiegel/amendo-client (https://github.com/DerSpiegel/amendo_php_client)',
        public ?string $apiKey = null,
        public int $httpRequestTimeout = 60,
        public readonly bool $verifySslCertificate = true
    ) {
    }
}
