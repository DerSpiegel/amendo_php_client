<?php

namespace DerSpiegel\AmendoClient\JobTicket;

use DOMElement;
use DOMNode;

/**
 * Class RunListFile.
 * @package DerSpiegel\AmendoClient\JobTicket
 */
class RunListFile extends PropertyList
{
    public const TYPE_FILE = 'File';
    public const TYPE_URI = 'Uri';
    public const TYPE_DOWNLOADURI = 'DownloadUri';

    private DOMElement $fileElement;


    /**
     * RunListFile constructor.
     * @param string $url URL or path to file.
     * @param string $type Type of RunListFile (File, Uri or DownloadUri)
     * @param DOMNode $runListNode
     */
    protected function __construct(
        protected string  $url,
        protected string  $type,
        protected DOMNode $runListNode
    )
    {
        $doc = $runListNode->ownerDocument;
        $this->fileElement = $doc->createElement('File');
        $this->fileElement = $runListNode->appendChild($this->fileElement);
        $this->fileElement->setAttribute($type, $url);
        parent::__construct($this->fileElement);
    }


    /**
     * Create RunListFile of type File.
     * @param string $path Path to file.
     * @param DOMElement $runListNode
     * @return RunListFile RunListFile instance.
     */
    public static function createFile(
        string  $path,
        DOMNode $runListNode
    ): self
    {
        return new RunListFile($path, self::TYPE_FILE, $runListNode);
    }


    /**
     * Create RunListFile of type Uri.
     * @param string $uri URI to file.
     * @param DOMNode $runListNode
     * @return RunListFile RunListFile instance.
     */
    public static function createUri(
        string  $uri,
        DOMNode $runListNode
    ): self
    {
        return new RunListFile($uri, self::TYPE_URI, $runListNode);
    }


    /**
     * Create RunListFile of type DownloadUri.
     * @param string $uri Download URI to file.
     * @param DOMNode $runListNode
     * @return RunListFile RunListFile instance.
     */
    public static function createDownloadUri(
        string  $uri,
        DOMNode $runListNode
    ): self
    {
        return new RunListFile($uri, self::TYPE_DOWNLOADURI, $runListNode);
    }
}
