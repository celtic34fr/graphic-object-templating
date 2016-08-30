<?php

namespace Objets\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODOnOff
 * @package Objets\ODContained
 *
 * setLabel
 * getLabel
 * setValue (surcharge)
 * getValue (surcharge
 * setLabelPosition
 * getLabelPosition
 * evtChange
 * disChange
 */
class ODOnOff extends ODContained
{

    const VALUE_ON    = 'OUI';
    const VALUE_OFF   = 'NON';

    const POSLABEL_LEFT  =  "left";
    const POSLABEL_RIGHT =  "right";

    const const_posLabel = "";
    const const_values   = "";

    public function __construct($id){
        $parent = parent::__construct($id, "oobject/odcontained/odonoff/odonoff.config.phtml");
        $this->properties = $parent->properties;
        $this->setDisplay();
    }

    public function setLabel($label = ""){
        $label = (string) $label;
        if (!empty($label)) {
            $properties = $this->getProperties();
            $properties['label'] 	   = $label;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getLabel( ){
        $properties = $this->getProperties();
        return (array_key_exists('label', $properties)) ? $properties['label'] : false;
    }

    public function setValue($value = self::VALUE_OFF) {
        $value = (string) $value;
        if (!empty($value)) {
            $values = $this->getValuesConst();
            if (!in_array($value, $values)) $value = self::VALUE_OFF;

            $properties = $this->getProperties();
            $properties['value'] = $value;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getValue( ){
        $properties = $this->getProperties();
        return (array_key_exists('value', $properties)) ? $properties['value'] : false;
    }

    public function setLabelPosition($position = self::POSLABEL_LEFT)
    {
        $position = (string) $position;
        if (!empty($position)) {
            $positions = $this->getPosLabelConst();
            if (!in_array($position, $positions)) $position = self::POSLABEL_LEFT;

            $properties = $this->getProperties();
            $properties['posLabel'] = $position;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getLabelPosition()
    {
        $properties = $this->getProperties();
        return (array_key_exists('posLabel', $properties) && !empty($properties['posLabel']))
            ? $properties['posLabel'] : false;
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

    private function getValuesConst()
    {
        $retour = [];
        if (empty($this->const_values)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'VALUE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_values = $retour;
        } else {
            $retour = $this->const_values;
        }
        return $retour;
    }

    private function getPosLabelConst()
    {
        $retour = [];
        if (empty($this->const_posLabel)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'POSLABEL');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_posLabel = $retour;
        } else {
            $retour = $this->const_posLabel;
        }
        return $retour;
    }

}
?>
