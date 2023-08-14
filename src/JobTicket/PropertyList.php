<?php

namespace DerSpiegel\AmendoClient\JobTicket;

use DOMElement;
use DOMException;
use DOMNode;

/**
 * Class PropertyList
 * @package DerSpiegel\AmendoClient\JobTicket
 */
abstract class PropertyList
{
    public const PROPERTY_STRING = 'PropertyString';
    public const PROPERTY_BOOLEAN = 'PropertyBoolean';
    public const PROPERTY_INTEGER = 'PropertyInteger';
    public const PROPERTY_FLOAT = 'PropertyFloat';
    public const PROPERTY_LIST = 'PropertyList';

    protected array $propertyLists = [];


    /**
     * PropertyList constructor.
     * @param DOMNode $parentNode Parent element to add the properties to.
     */
    protected function __construct(
        protected DOMNode $parentNode
    )
    {
    }


    /**
     * Set string property with the specified name on the specified
     * property list.
     * @param string $list List name.
     * @param string $name Property name.
     * @param string $value Property string value.
     */
    public function setStringProperty(
        string $list,
        string $name,
        string $value
    ): void {
        $listElement = $this->getPropertyList($list);
        $property = $this->createSubProperty(
            $name, self::PROPERTY_STRING, $value);
        $listElement->appendChild($property);
    }


    /**
     * Set boolean property with the specified name on the specified
     * property list.
     * @param string $list List name.
     * @param string $name Property name.
     * @param bool $value Property boolean value.
     * @throws DOMException
     */
    public function setBooleanProperty(
        string $list,
        string $name,
        bool $value
    ): void {
        $listElement = $this->getPropertyList($list);
        $property = $this->createSubProperty(
            $name, self::PROPERTY_BOOLEAN, $value ? 'true' : 'false');
        $listElement->appendChild($property);
    }


    /**
     * Set integer property with the specified name on the specified
     * property list.
     * @param string $list List name.
     * @param string $name Property name.
     * @param int $value Property integer value.
     * @throws DOMException
     */
    public function setIntegerProperty(
        string $list,
        string $name,
        int $value
    ): void {
        $listElement = $this->getPropertyList($list);
        $property = $this->createSubProperty(
            $name, self::PROPERTY_INTEGER, strval($value));
        $listElement->appendChild($property);
    }


    /**
     * Set float property with the specified name on the specified
     * property list.
     * @param string $list List name.
     * @param string $name Property name.
     * @param float $value Property float value.
     * @throws DOMException
     */
    public function setFloatProperty(
        string $list,
        string $name,
        float $value
    ): void {
        $listElement = $this->getPropertyList($list);
        $property = $this->createSubProperty(
            $name, self::PROPERTY_FLOAT, strval($value));
        $listElement->appendChild($property);
    }


    /**
     * Get property list DOMElement.
     * If the list does not yet exist, a new list is created.
     * @param string $list Property list name.
     * @return DOMNode DOMNode of property list.
     * @throws DOMException
     */
    protected function getPropertyList(string $list): DOMNode
    {
        if (array_key_exists($list, $this->propertyLists)) {
            return $this->propertyLists[$list];
        }
        $doc = $this->parentNode->ownerDocument;
        $propertyListElement = $doc->createElement('Property');
        $propertyListNode = $this->parentNode->insertBefore($propertyListElement,
            $this->parentNode->firstChild);
        $propertyListNode->setAttributeNS(
            'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:type', self::PROPERTY_LIST);
        $nameElement = $doc->createElement('Name');
        $nameNode = $propertyListNode->appendChild($nameElement);
        $nameNode->appendChild($doc->createTextNode($list));
        $this->propertyLists[$list] = $propertyListNode;
        return $propertyListNode;
    }


    /**
     * Create a new SubProperty DOMElement.
     * @param string $name Property name.
     * @param string $type Property type.
     * @param string $value Property value.
     * @return DOMElement DOMElement of property.
     */
    protected function createSubProperty(
        string $name,
        string $type,
        string $value
    ): DOMElement {
        $doc = $this->parentNode->ownerDocument;
        $propertyElement = $doc->createElement('SubProperty');
        $propertyElement->setAttribute('xsi:type', $type);
        $nameElement = $doc->createElement('Name');
        $nameElement = $propertyElement->appendChild($nameElement);
        $nameElement->appendChild($doc->createTextNode($name));
        $valueElement = $doc->createElement('Value');
        $valueElement = $propertyElement->appendChild($valueElement);
        $valueElement->appendChild($doc->createTextNode($value));
        return $propertyElement;
    }

}
