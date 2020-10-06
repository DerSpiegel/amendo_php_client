<?php

namespace DerSpiegel\AmendoClient;

use Psr\Log\LoggerInterface;


class AmendoClient
{
    protected AmendoConfig $config;
    protected LoggerInterface $logger;


    /**
     * AmendoClient constructor.
     * @param AmendoConfig $config
     * @param LoggerInterface $logger
     */
    public function __construct(AmendoConfig $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }
}