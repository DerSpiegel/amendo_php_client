<?php

use DerSpiegel\AmendoClient\AmendoConfig;
use DerSpiegel\AmendoClient\AmendoClient;
use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

require_once 'vendor/autoload.php';

$logger = new Logger('amendoClient');    // A logger is required
$logger->pushHandler(new ErrorLogHandler());  // Log to PHP error_log

$amendoConfig = new AmendoConfig(
    'http://amendo.example.com' // Amendo base URL
);

$amendoClient = new AmendoClient($amendoConfig, $logger); // Create client

// TODO: Add an example Amendo call

$logger->debug(file_get_contents($amendoConfig->getUrl() . '/ws_core_gateway/CommonWeb?wsdl'));
