<?php

namespace GraphicObjectTemplating\Objects;

use GraphicObjectTemplating\Objects\OObject;

/**
 * Class OSContainer
 * @package GraphicObjectTemplating\Objects
 *
 * width
 * height
 * children
 * 
 * setWidth
 * getWidth
 * setHeight
 * getHeight
 * addChild
 * removeChild
 * getChild
 * isChild
 * hasChild
 * howManyChildren
 * getChildren
 * convertValue
 * getConverted
 */
class OSContainer extends OObject
{

    public function __construct($id, $adrProperties)
    {
        $properties = include(__DIR__ . "/../../../view/graphic-object-templating/oobject/oscontainer/oscontainer.config.php");
        parent::__construct($id, $adrProperties);
        $properties = array_merge($this->getProperties(), $properties);
        $this->setProperties($properties);
    }

    public function __get($nameChild)
    {
        $properties = $this->getProperties();
        if (!empty($properties['children'])) {
            foreach ($properties['children'] as $idChild => $child) {
                $obj = OObject::buildObject($idChild);
                $name = $obj->getName();
                if ($name == $nameChild) return $obj;
            }
        }
        return false;
    }

    public function __set($nameChild, $value)
    {
        if (OObject::existObject($nameChild)) {
            $gotObjList = OObject::validateSession();
            $objects = $gotObjList->offsetGet('objects');
            $properties = unserialize($objects[$nameChild], ['allowed_classes' => true]);
            if (array_key_exists('value', $properties)) { $properties['value'] = $value; }
            $objects[$nameChild] = serialize($properties);
            $gotObjList->offsetSet('objects', $objects);
        }
        return $this;
    }

    public function __isset($nameChild)
    {
        return OObject::existObject($nameChild);
    }

    public function setWidth($width)
    {
        $width               = (string) $width;
        $properties          = $this->getProperties();
        $properties['width'] = $width;
        $this->setProperties($properties);
        return $this;
    }

    public function getWidth()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('width', $properties)) ? $properties['width'] : false);
    }

    public function setHeight($height)
    {
        $height               = (int) $height;
        $properties           = $this->getProperties();
        $properties['height'] = $height;
        $this->setProperties($properties);
        return $this;
    }

    public function getHeight()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('height', $properties)) ? $properties['height'] : false);
    }

    public function addChild(OObject $child) {
        $properties = $this->getProperties();
        $properties['children'][$child->getId()] =
            array($child->getId(), $child->getConverted());
        $this->setProperties($properties);
        return $this;
    }

    public function removeChild(OObject $child)
    {
        $properties = $this->getProperties();
        if (array_key_exists($child->getId(), $properties['children'])) {
            unset($properties['children'][$child->getId()]);
            $this->setProperties($properties);
        }
    }

    public function getChild($idChild)
    {
        $properties = $this->getProperties();
        if (array_key_exists($idChild, $properties['children']))
            return OObject::buildObject($idChild);
        return false;
    }

    public function isChild($idChild)
    {
        $properties = $this->getProperties();
        $children = $properties['children'];
        if (!empty($children)){
            if (array_key_exists($idChild, $children)) return true;
        }
        return false;
    }

    public function hasChildren()
    {
        $properties = $this->getProperties();
        return ((sizeof($properties['children']) > 0) ? true : false);
    }

    public function howManyChildren()
    {
        $properties = $this->getProperties();
        return ((isset($properties['children']) && is_array($properties['children']) ) ? sizeof($properties['children']) : false);
    }

    public function getChildren() {
        $properties = $this->getProperties();
        $children   = $properties['children'];
        if (!empty($children)) {
            $arrayChildren = [];
            foreach ($children as $idChild => $child) {
                $obj = OObject::buildObject($idChild);
                $arrayChildren[] = $obj;
            }
            return $arrayChildren;
        }
        return false ;
    }

    public function convertValue($value) { return $this->setProperties($value); }

    public function getConverted() { return $this->getProperties(); }

}