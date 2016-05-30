<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 10:42
 */

namespace GraphicObjectTemplating\Objects;

use GraphicObjectTemplating\Objects\OObject;

/**
 * Class OSContainer
 * @package GraphicObjectTemplating\Objects
 *
 * width
 * height
 * children
 */
class OSContainer extends OObject
{

    public function __construct($id, $adrProperties)
    {
        $properties = include(__DIR__ ."/../../../view/graphic-object-templating/oobject/oscontainer/oscontainer.config.phtml");
        parent::__construct($id, $adrProperties);
        $properties = array_merge($this->getProperties(), $properties->toArray());
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

    public function setWidth($width)
    {
        $width               = (int) $width;
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