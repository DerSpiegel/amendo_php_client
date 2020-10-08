<?php

namespace DerSpiegel\AmendoClient;

/**
 * Class AmendoConfig.
 * @package DerSpiegel\AmendoClient
 */
class AmendoConfig
{
    protected ?string $wsdl = null;
    protected array $soapClientOptions = array();

    /**
     * AmendoConfig constructor.
     * @param string $baseUrl Base URL of Amendo server.
     */
    public function __construct(string $baseUrl = null)
    {
        if ($baseUrl !== null) {
            $this->wsdl = $baseUrl . '/ws_core_gateway/CommonWeb?wsdl';
        }
    }

    /**
     * Get path/URI to Amendo WSDL file.
     * @return ?string WSDL file or URL if set.
     */
    public function getWsdl(): ?string
    {
        return $this->wsdl;
    }

    /**
     * Set path/URI to Amendo WSDL file.
     * @param string $wsdl WSDL file or URL.
     */
    public function setWsdl(string $wsdl): void
    {
        $this->wsdl = $wsdl;
    }

    /**
     * Get SoapClient options array.
     * @return array SoapClient options array.
     */
    public function getSoapClientOptions(): array
    {
        return $this->soapClientOptions;
    }

    /**
     * Get SoapClient option value.
     * See PHP SoapClient documentation for details.
     * @param string $key Option key.
     * @return mixed Option value.
     */
    public function getSoapClientOption(string $key)
    {
	if (!array_key_exists($key, $this->soapClientOptions)) return null;
        return $this->soapClientOptions[$key];
    }

    /**
     * Set SoapClient option value.
     * @param string $key Option key.
     * @param mixed $value Option value.
     */
    public function setSoapClientOption(string $key, $value): void
    {
        $this->soapClientOptions[$key] = $value;
    }

}
