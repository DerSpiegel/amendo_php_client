<?php

namespace DerSpiegel\AmendoClient;


class AmendoConfig
{
    public function __construct(
        // Base server URL without trailing slash, e.g. "https://amendo.example.com"
        public readonly string $baseUrl,
        public string          $httpUserAgent = 'der-spiegel/amendo-client (https://github.com/DerSpiegel/amendo_php_client)',
        public ?string         $apiKey = null,
        public int             $httpRequestTimeout = 60,
        public readonly bool   $verifySslCertificate = true
    )
    {
    }
}
