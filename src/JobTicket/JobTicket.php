<?php

namespace DerSpiegel\AmendoClient\JobTicket;

use DOMDocument;
use DOMElement;
use DerSpiegel\AmendoClient\AmendoClientException;

/**
 * Class JobTicket.
 * @package DerSpiegel\AmendoClient\JobTicket
 */
abstract class JobTicket extends PropertyList
{
    protected DOMDocument $domDocument;
    protected DOMElement $jobElement;
    protected DOMElement $runListElement;
    protected ?DOMElement $priorityElement = null;
    protected array $runListFiles = array();

    /**
     * JobTicket constructor.
     * @param string $jobElementName Name of XML root element.
     */
    protected function __construct(string $jobElementName)
    {
        $this->domDocument = new DOMDocument();
        $this->domDocument->standalone = true;
        $this->domDocument->formatOutput = true;
        $this->jobElement = $this->domDocument->createElement($jobElementName);
        $this->jobElement = $this->domDocument->appendChild($this->jobElement);
        $this->jobElement->setAttribute('Name', 'PHP AmendoClient-' . time());
        $this->runListElement = $this->domDocument->createElement('RunList');
        $this->runListElement =
                $this->jobElement->appendChild($this->runListElement);
        $this->runListElement->setAttribute('ID', '');
        parent::__construct($this->jobElement);
    }

    /**
     * Get array of added RunListFiles.
     * @return array Array of RunListFile instances.
     */
    public function getRunListFiles(): array
    {
        return $this->runListFiles;
    }


    /**
     * Set JobTicket job name.
     * @param string $name Name attribute of XML root element.
     */
    public function setJobName(string $name): void
    {
        $this->jobElement->setAttribute('Name', $name);
    }

    /**
     * Set JobTicket job priority.
     * @param int $priority Job priority (range: 1-100).
     */
    public function setJobPriority(int $priority): void
    {
        if ($this->priorityElement === null) {
            $this->priorityElement = 
                    $this->domDocument->createElement('Priority');
            $this->priorityElement = $this->jobElement->insertBefore(
                    $this->priorityElement, $this->runListElement->nextSibling);
        }
        $this->setElementText($this->priorityElement, strval($priority));
    }

    /**
     * Add file to JobTicket run list.
     * @param string $path Path to source file.
     * @return RunListFile RunListFile instance.
     */
    public function addFile(string $path): RunListFile
    {
        $file = RunListFile::createFile($path, $this->runListElement);
        $this->runListFiles[] = $file;
        return $file;
    }

    /**
     * Add URI to JobTicket run list.
     * @param string $uri URI to source file.
     * @return RunListFile RunListFile instance.
     */
    public function addUri(string $uri): RunListFile
    {
        $file = RunListFile::createUri($uri, $this->runListElement);
        $this->runListFiles[] = $file;
        return $file;
    }

    /**
     * Add download URI to JobTicket run list.
     * @param string $uri Download URI to source file.
     * @return RunListFile RunListFile instance.
     */
    public function addDownloadUri(string $uri): RunListFile
    {
        $file = RunListFile::createDownloadUri($uri, $this->runListElement);
        $this->runListFiles[] = $file;
        return $file;
    }

    /**
     * Get JobTicket XML as string.
     * @return string JobTicket XML as string.
     */
    public function getData(): string
    {
        return $this->domDocument->saveXml();
    }

    /**
     * Set text on DOMElement.
     * @param DOMElement $element Element to set text on.
     * @param string $text New text.
     */
    protected function setElementText(DOMElement $element, string $text): void
    {
        while ($element->hasChildNodes()) {
            $element->removeChild($element->firstChild);
        }
        $element->appendChild($this->domDocument->createTextNode($text));
    }

}
