<?php

use DerSpiegel\AmendoClient\AmendoConfig;
use DerSpiegel\AmendoClient\AmendoClient;
use DerSpiegel\AmendoClient\JobTicket\SimpleJobTicket;
use Monolog\Logger;
use Monolog\Handler\ErrorLogHandler;

require_once 'vendor/autoload.php';

// Create logger
$logger = new Logger('AmendoClient');
$logger->pushHandler(new ErrorLogHandler());

// Amendo server base URL
$amendoServerUrl = getenv('AMENDO_SERVER') ?
        getenv('AMENDO_SERVER') : 'http://amendo.example.com';

// Create AmendoConfig object
$amendoConfig = new AmendoConfig(
    baseUrl: $amendoServerUrl,
    // Set API key (optional):
    apiKey: 'SECRET',
    // Change connection timeout (optional):
    httpRequestTimeout: 5
);

// Create AmendoClient object
$amendoClient = new AmendoClient($amendoConfig, $logger);

// Create SimpleJobTicket object
$jobTicket = new SimpleJobTicket();

// Set Amendo assembly line
$assemblyLine = getenv('AMENDO_ASSEMBLYLINE') ?
        getenv('AMENDO_ASSEMBLYLINE'): 'Example';
$jobTicket->setAssemblyLineReference($assemblyLine);

// Set job priority (optional)
$jobTicket->setJobPriority(60);

// Set job name
$jobTicket->setJobName('Test-' . time());

// Add job properties (optional)
$jobTicket->setStringProperty('Custom', 'AString', 'Text');
$jobTicket->setBooleanProperty('Custom', 'ABool', true);
$jobTicket->setIntegerProperty('Custom', 'AnInteger', 42);
$jobTicket->setFloatProperty('Custom', 'AFloat', 0.815);

// Add file to job ticket
$filePath = getenv('AMENDO_FILEPATH') ?
        getenv('AMENDO_FILEPATH') : '/tmp/example.tif';
$file = $jobTicket->addFile($filePath);

// Add file properties (optional)
$file->setStringProperty('Custom', 'AString', 'FileText');
$file->setBooleanProperty('Custom', 'ABool', false);
$file->setIntegerProperty('Custom', 'AnInteger', 15);
$file->setFloatProperty('Custom', 'AFloat', 3.1415);

// Submit job ticket to Amendo
$jobId = $amendoClient->startJobTicket($jobTicket);
echo "JOB ID: $jobId\n";

// Job status & result
if ($jobId) {
    for ($i = 0; $i < 3; $i++) {
        if ($i) sleep(10);
        $result = $amendoClient->getJobOverview($jobId);
        echo "RESULT:\n";
        var_dump($result);
        echo "\n";
    }
}
