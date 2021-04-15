<?php

namespace DerSpiegel\AmendoClient;

use DerSpiegel\AmendoClient\JobTicket\JobTicket;
use Exception;
use Psr\Log\LoggerInterface;
use SoapClient;

/**
 * Class AmendoClient.
 * @package DerSpiegel\AmendoClient
 */
class AmendoClient
{
    protected AmendoConfig $config;
    protected SoapClient $soapClient;
    protected LoggerInterface $logger;


    /**
     * AmendoClient constructor.
     * @param AmendoConfig $config AmendoClient configuration.
     * @param LoggerInterface $logger Logger to use.
     */
    public function __construct(
        AmendoConfig $config,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->logger = $logger;
        $wsdl = $config->getWsdl();
        if ($wsdl === null) {
            throw new AmendoClientException(__METHOD__ . ': Configuration ' .
                'incomplete. WSDL location has not been set.');
        }
        $this->logger->info(
            "Using WSDL from <{$wsdl}>",
            [
                'method' => __METHOD__,
                'wsdl' => $wsdl
            ]);
        $options = $config->getSoapClientOptions();
        try {
            $this->soapClient = new SoapClient($wsdl, $options);
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ': failed: ' . $ex->getMessage(),
                $ex->getCode(), $ex);
        }
    }


    /**
     * @return AmendoConfig
     */
    public function getConfig(): AmendoConfig
    {
        return $this->config;
    }


    /**
     * Get underlying SoapClient instance.
     * @return SoapClient instance.
     */
    public function getSoapClient(): SoapClient
    {
        return $this->soapClient;
    }


    /**
     * Start job ticket.
     * @param JobTicket $ticket JobTicket instance to start.
     * @return int OneVison Workspace JobTicket ID or 0 on error.
     */
    public function startJobTicket(JobTicket $ticket): int
    {
        try {
            $jobId = $this->soapClient->startJobTicket(
                urlencode($ticket->getData()));
            $this->logger->info(
                "Created new Amendo job with job id <{$jobId}>",
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
     * Get status of job.
     * @param int $jobId OneVision Workspace job id.
     * @return string Status string.
     */
    public function getStatus(int $jobId): string
    {
        try {
            $status = $this->soapClient->status($jobId);
            $this->logger->info(
                "Status of Amendo job <{$jobId}> is <{$status}>",
                [
                    'method' => __METHOD__,
                    'jobId' => $jobId,
                    'status' => $status
                ]);
            return $status;
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ": failed for Amendo job <{$jobId}>: " .
                $ex->getMessage(), $ex->getCode(), $ex);
        }
    }


    /**
     * Get result of job.
     * @param int $jobId OneVision Workspace job id.
     * @return mixed Result object.
     */
    public function getResult(int $jobId)
    {
        try {
            $result = $this->soapClient->result($jobId);
            $this->logger->info(
                "Result of Amendo job <{$jobId}> is <" .
                print_r($result, true) . ">",
                [
                    'method' => __METHOD__,
                    'jobId' => $jobId,
                    'result' => $result
                ]);
            return $result;
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ": failed for Amendo job <{$jobId}>: " .
                $ex->getMessage(), $ex->getCode(), $ex);
        }
    }


    /**
     * Pause job.
     * @param int $jobId OneVision Workspace job id.
     * @return string Status string.
     */
    public function pause(int $jobId): string
    {
        try {
            $status = $this->soapClient->pause($jobId);
            $this->logger->info(
                "Pause status of Amendo job <{$jobId}> is <{$status}>",
                [
                    'method' => __METHOD__,
                    'jobId' => $jobId,
                    'status' => $status
                ]);
            return $status;
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ": failed for Amendo job <{$jobId}>: " .
                $ex->getMessage(), $ex->getCode(), $ex);
        }
    }


    /**
     * Resume job.
     * @param int $jobId OneVision Workspace job id.
     * @return string Status string.
     */
    public function resume(int $jobId): string
    {
        try {
            $status = $this->soapClient->resume($jobId);
            $this->logger->info(
                "Resume status of Amendo job <{$jobId}> is <{$status}>",
                [
                    'method' => __METHOD__,
                    'jobId' => $jobId,
                    'status' => $status
                ]);
            return $status;
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ": failed for Amendo job <{$jobId}>: " .
                $ex->getMessage(), $ex->getCode(), $ex);
        }
    }


    /**
     * Cancel job.
     * @param int $jobId OneVision Workspace job id.
     * @return string Status string.
     */
    public function cancel(int $jobId): string
    {
        try {
            $status = $this->soapClient->cancel($jobId);
            $this->logger->info(
                "Cancel status of Amendo job <{$jobId}> is <{$status}>",
                [
                    'method' => __METHOD__,
                    'jobId' => $jobId,
                    'status' => $status
                ]);
            return $status;
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ": failed for Amendo job <{$jobId}>: " .
                $ex->getMessage(), $ex->getCode(), $ex);
        }
    }


    /**
     * Cancel and delete job.
     * @param int $jobId OneVision Workspace job id.
     * @return string Status string.
     */
    public function cancelAndDelete(int $jobId): string
    {
        try {
            $status = $this->soapClient->cancelAndDelete($jobId);
            $this->logger->info(
                "CancelAndDelete status of Amendo job <{$jobId}> " .
                "is <{$status}>",
                [
                    'method' => __METHOD__,
                    'jobId' => $jobId,
                    'status' => $status
                ]);
            return $status;
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ": failed for Amendo job <{$jobId}>: " .
                $ex->getMessage(), $ex->getCode(), $ex);
        }
    }


    /**
     * Delete job.
     * @param int $jobId OneVision Workspace job id.
     * @return string Status string.
     */
    public function delete(int $jobId): string
    {
        try {
            $status = $this->soapClient->deleteFromStorage($jobId);
            $this->logger->info(
                "Delete status of Amendo job <{$jobId}> is <{$status}>",
                [
                    'method' => __METHOD__,
                    'jobId' => $jobId,
                    'status' => $status
                ]);
            return $status;
        } catch (Exception $ex) {
            throw new AmendoClientException(
                __METHOD__ . ": failed for Amendo job <{$jobId}>: " .
                $ex->getMessage(), $ex->getCode(), $ex);
        }
    }
}
