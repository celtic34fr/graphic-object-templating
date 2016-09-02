<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 01/09/16
 * Time: 19:18
 */

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODTextarea
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * setLabel
 * getLabel
 * evtChange
 * disChange
 * setPlaceholder
 * getPlaceholder
 * setCols
 * getCols
 * setRows
 * getRows
 * enaReadOnly
 * disReadOnly
 * setMaxLength
 * getMaxLength
 */
class ODTextarea extends ODContained
{
    public function __construct($id)
    {
        $parent = parent::__construct($id, "oobject/odcontained/odtextarea/odtextarea.config.phtml");
        $this->properties = $parent->properties;
        $this->setDisplay();
    }

    public function setLabel($label)
    {
        $label               = (string) $label;
        $properties          = $this->getProperties();
        $properties['label'] = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['label'])) ? $properties['label'] : false) ;
    }

    public function evtChange($callback)
    {
        $callback = (string) $callback;
        $properties             = $this->getProperties();
        if(!isset($properties['event'])) $properties['event'] = [];
        if(!is_array($properties['event'])) $properties['event'] = [];
        $properties['event']['change'] = $callback;

        $this->setProperties($properties);
        return $this;
    }

    public function disChange()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']['change'])) unset($properties['event']['change']);
        $this->setProperties($properties);
        return $this;
    }

    public function setPlaceholder($placeholder)
    {
        $placeholder = (string) $placeholder;
        $properties = $this->getProperties();
        $properties['placeholder'] = $placeholder;
        $this->setProperties($properties);
        return $this;
    }

    public function getPlaceholder()
    {
        $properties = $this->getProperties();
        return (array_key_exists('placeholder', $properties) ? $properties['placeholder'] : false);
    }

    public function setCols($cols)
    {
        $cols = (int) $cols;
        $propperties = $this->getProperties();
        $propperties['cols'] = $cols;
        $this->setProperties($propperties);
        return $this;
    }

    public function getCols()
    {
        $properties = $this->getProperties();
        return (array_key_exists('cols', $properties) ? $properties['cols'] : false);
    }

    public function setRows($rows)
    {
        $rows = (int) $rows;
        $propperties = $this->getProperties();
        $propperties['rows'] = $rows;
        $this->setProperties($propperties);
        return $this;
    }

    public function getRows()
    {
        $properties = $this->getProperties();
        return (array_key_exists('rows', $properties) ? $properties['rows'] : false);
    }

    public function enaReadOnly()
    {
        $properties = $this->getProperties();
        $properties['readOnly'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disReadOnly()
    {
        $properties = $this->getProperties();
        $properties['readOnly'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function setMaxLength($maxLength)
    {
        $maxLength = (int) $maxLength;
        $propperties = $this->getProperties();
        $propperties['maxLength'] = $maxLength;
        $this->setProperties($propperties);
        return $this;
    }

    public function getMaxLength()
    {
        $properties = $this->getProperties();
        return (array_key_exists('maxLength', $properties) ? $properties['maxLength'] : false);
    }

}