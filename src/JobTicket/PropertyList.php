<?php

namespace DerSpiegel\AmendoClient\JobTicket;

use DOMElement;

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

    protected DOMElement $parentElement;
    protected array $propertyLists = array();


    /**
     * PropertyList constructor.
     * @param DOMElement $parentElement Parent element to add the properties to.
     */
    protected function __construct(DOMElement $parentElement)
    {
        $this->parentElement = $parentElement;
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
     * @param string $value Property boolean value.
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
     * @param string $value Property integer value.
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
     * @param string $value Property float value.
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
     * @return DOMElement DOMElement of property list.
     */
    protected function getPropertyList(string $list): DOMElement
    {
        if (array_key_exists($list, $this->propertyLists)) {
            return $this->propertyLists[$list];
        }
        $doc = $this->parentElement->ownerDocument;
        $propertyList = $doc->createElement('Property');
        $propertyList = $this->parentElement->insertBefore($propertyList,
            $this->parentElement->firstChild);
        $propertyList->setAttributeNS(
            'http://www.w3.org/2001/XMLSchema-instance',
            'xsi:type', self::PROPERTY_LIST);
        $nameElement = $doc->createElement('Name');
        $nameElement = $propertyList->appendChild($nameElement);
        $nameElement->appendChild($doc->createTextNode($list));
        $this->propertyLists[$list] = $propertyList;
        return $propertyList;
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
        $doc = $this->parentElement->ownerDocument;
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
