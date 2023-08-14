<?php

namespace DerSpiegel\AmendoClient\JobTicket;

use DerSpiegel\AmendoClient\AmendoClientException;
use DOMElement;

/**
 * Class SimpleJobTicket
 * @package DerSpiegel\AmendoClient\JobTicket
 */
class SimpleJobTicket extends JobTicket
{
    protected ?DOMElement $assemblyLineRefElement = null;


    /**
     * SimpleJobTicket constructor.
     */
    public function __construct()
    {
        parent::__construct('Job');
    }


    /**
     * Set AssemblyLineReference.
     * @param string $reference Assembly line name.
     */
    public function setAssemblyLineReference(string $reference): void
    {
        if ($this->assemblyLineRefElement === null) {
            $this->assemblyLineRefElement =
                $this->domDocument->createElement('AssemblyLineReference');
            $this->assemblyLineRefElement = $this->jobElement->appendChild(
                $this->assemblyLineRefElement);
        }
        $this->setElementText($this->assemblyLineRefElement, $reference);
    }


    /**
     * Get JobTicket XML as string.
     * @return string JobTicket XML as string.
     */
    public function toXml(): string
    {
        if ($this->assemblyLineRefElement === null) {
            throw new AmendoClientException(__METHOD__ . ': SimpleJobTicket ' .
                'incomplete. No AssemblyLineReference has been set.');
        }
        return parent::toXml();
    }
}
