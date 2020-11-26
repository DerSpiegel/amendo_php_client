<?php

namespace DerSpiegel\AmendoClient\JobTicket;

use DOMElement;

/**
 * Class RunListFile.
 * @package DerSpiegel\AmendoClient\JobTicket
 */
class RunListFile extends PropertyList
{
    public const TYPE_FILE = 'File';
    public const TYPE_URI = 'Uri';
    public const TYPE_DOWNLOADURI = 'DownloadUri';

    private string $url;
    private string $type;
    private DOMElement $runListElement;
    private DOMElement $fileElement;


    /**
     * RunListFile constructor.
     * @param string $url URL or path to file.
     * @param string $type Type of RunListFile (File, Uri or DownloadUri)
     * @param DOMElement $runListElement
     */
    protected function __construct(
        string $url,
        string $type,
        DOMElement $runListElement
    ) {
        $this->url = $url;
        $this->type = $type;
        $this->runListElement = $runListElement;
        $doc = $runListElement->ownerDocument;
        $this->fileElement = $doc->createElement('File');
        $this->fileElement = $runListElement->appendChild($this->fileElement);
        $this->fileElement->setAttribute($type, $url);
        parent::__construct($this->fileElement);
    }


    /**
     * Create RunListFile of type File.
     * @param string $path Path to file.
     * @param DOMElement $runListElement
     * @return RunListFile RunListFile instance.
     */
    public static function createFile(
        string $path,
        DOMElement $runListElement
    ): self {
        return new RunListFile($path, self::TYPE_FILE, $runListElement);
    }


    /**
     * Create RunListFile of type Uri.
     * @param string $uri URI to file.
     * @param DOMElement $runListElement
     * @return RunListFile RunListFile instance.
     */
    public static function createUri(
        string $uri,
        DOMElement $runListElement
    ): self {
        return new RunListFile($uri, self::TYPE_URI, $runListElement);
    }


    /**
     * Create RunListFile of type DownloadUri.
     * @param string $uri Download URI to file.
     * @param DOMElement $runListElement
     * @return RunListFile RunListFile instance.
     */
    public static function createDownloadUri(
        string $uri,
        DOMElement $runListElement
    ): self {
        return new RunListFile($uri, self::TYPE_DOWNLOADURI, $runListElement);
    }
}
