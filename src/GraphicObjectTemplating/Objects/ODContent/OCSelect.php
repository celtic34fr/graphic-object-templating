<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 07/07/16
 * Time: 16:14
 */

namespace GraphicObjectTemplating\Objects\ODContent;

use GraphicObjectTemplating\Objects\ODContent;

class OCSelect extends ODContent
{
    public function __construct($id) {
        $parent = parent::__construct($id, "oobject/odcontent/ocselect/ocselect.config.phtml");
        $this->properties = $parent->properties;
        $this->setDisplay();
    }

    public function addOption($value, $libel)
    {
        $properties = $this->getProperties();
        if (!array_key_exists('options', $properties)) $properties['options'] = [];
        $properties['options'][$value] = $libel;
        $this->setProperties($properties);
        return $this;
    }

    public function removeOption($value)
    {
        $properties = $this->getProperties();
        if (!array_key_exists('options', $properties)) {
            if (array_key_exists($value, $properties['options'])) {
                unset($properties['options'][$value]);
                return $this;
            }
        }
        return false;
    }

    public function clearOptions()
    {
        $properties = $this->getProperties();
        $properties['options'] = [];
        $this->setProperties($properties);
        return $this;
    }

    public function setOptions(array $options)
    {
        $properties = $this->getProperties();
        if (!array_key_exists('options', $properties)) $properties['options'] = [];
        $properties['options'] = $options;
    }

    public function getOptions()
    {
        $properties = $this->getProperties();
        return (array_key_exists('options', $properties) ? $properties['options'] : false);
    }
}