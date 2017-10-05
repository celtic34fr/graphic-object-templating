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
 * addCssCode($nom, $code)
 * setCssCodes(array $codes)
 * getCssCode($nom)
 * getCodes()
 * rmCodeCss($nom)
 */
class OSContainer extends OObject
{
	const MODE_LAST     = "Last";
	const MODE_FIRST    = "First";
	const MODE_BEFORE   = "Before";
	const MODE_AFTER    = "After";
	const MODE_NTH      = "Nth";

    public function __construct($id, $adrProperties = null)
    {
        $path  = __DIR__ ;
        $path .= "/../../view/graphic-object-templating/oobject/oscontainer/oscontainer.config.php";
        $properties = include($path);
        parent::__construct($id, $adrProperties);
        foreach ($this->properties as $key => $property) {
            $properties[$key] = $property;
        }
        $this->setProperties($properties);
        return $this;
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

    public function addChild(OObject $child, $mode = self::MODE_LAST, $param = null) {
        $properties = $this->getProperties();
        switch($mode) {
			case self::MODE_LAST:
				$properties['children'][$child->getId()] =
					array($child->getId(), $child->getConverted());
				break;
			case self::MODE_FIRST;
				$children = $properties['children'];
				$properties['children'] = [];
				$properties['children'][$child->getId()] =
					array($child->getId(), $child->getConverted());
				$properties['children'] = array_merge($properties['children'], $children);
				break;
			case self::MODE_BEFORE;
			case self::MODE_AFTER;
				if (!empty($param) && OObject::existObject($param) && $this->isChild($param)) {
					$newChildren = [];
					foreach ($properties['children'] as $id => $rChild) {
						if ($id == $param && $mode == self::MODE_BEFORE) {
							$newChildren[$child->getId()] =
								array($child->getId(), $child->getConverted());
						}
						$newChildren[$id] = $rChild;
						if ($id == $param && $mode == self::MODE_AFTER) {
							$newChildren[$child->getId()] =
								array($child->getId(), $child->getConverted());
						}
					}
					$properties['children'] = $newChildren;
				}
				break;
            case self::MODE_NTH:
                if (!empty($param) && (int) $param > 0 && (int) $param <= count($properties['children'])) {
                    $compteur = 0;
                    $newChildren = [];
                    foreach ($properties['children'] as $id => $rChild) {
                        $compteur++;
                        if ($compteur == (int)$param) {
                            $newChildren[$child->getId()] =
                                array($child->getId(), $child->getConverted());
                        }
                        $newChildren[$id] = $rChild;
                    }
                    $properties['children'] = $newChildren;
                }
                break;
		}
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

    public function convertValue($value) {
        $properties = $this->getProperties();
        $properties['children'] = $value;
        return $this->setProperties($properties);
    }

    public function getConverted() {
        $properties = $this->getProperties();
        return $properties['children'];
    }


    public function addCssCode($nom, $code)
    {
        $nom        = (string) $nom;
        $code       = (string) $code;
        $properties = $this->getProperties();
        $cssCode    = $properties['cssCode'];
        if (!array_key_exists($nom, $cssCode)) {
            $cssCode[$nom]          = $code;
            $properties['cssCode']  = $cssCode;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function setCssCodes(array $codes)
    {
        $properties             = $this->getProperties();
        $properties['cssCode']  = $codes;
        $this->setProperties($properties);
        return $this;
    }

    public function getCssCode($nom)
    {
        $nom        = (string) $nom;
        $properties = $this->getProperties();
        $cssCode    = $properties['cssCode'];
        if (array_key_exists($nom, $cssCode)) { return $cssCode[$nom]; }
        return false;
    }

    public function getCssCodes()
    {
        $properties = $this->getProperties();
        return (array_key_exists('cssCode', $properties) ? $properties['cssCode'] : false);
    }

    public function rmCssCode($nom)
    {
        $nom        = (string) $nom;
        $properties = $this->getProperties();
        $cssCode    = $properties['cssCode'];
        if (array_key_exists($nom, $cssCode)) {
            unset($cssCode[$nom]);
            $properties['cssCode'] = $cssCode;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

}