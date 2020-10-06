<?php

namespace DerSpiegel\AmendoClient;


/**
 * Class AmendoConfig
 * @package DerSpiegel\AmendoClient
 */
class AmendoConfig
{
    protected string $url;


    /**
     * AmendoConfig constructor.
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
    }


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}