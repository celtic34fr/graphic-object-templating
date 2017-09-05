<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODRadio
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * addOptions
 * removeOption
 * setOptions
 * getOptions
 * check
 * uncheck
 * uncheckAll
 * getCheck
 * setLabel
 * getLabel
 * evtClick
 * disClick
 * evtChange
 * disChange
 * setForme
 * getForme
 * setPlacement
 * getPlacement
 */
class ODRadio extends ODContained
{
    const RADIO_CHECK = "check";
    const RADIO_UNCHECK = "uncheck";

    const RADIOTYPE_DEFAULT = "radio";
    const RADIOTYPE_PRIMARY = "radio radio-primary";
    const RADIOTYPE_SUCCESS = "radio radio-success";
    const RADIOTYPE_INFO    = "radio radio-info";
    const RADIOTYPE_WARNING = "radio radio-warning";
    const RADIOTYPE_DANGER  = "radio radio-danger";

    const RADIOFORME_HORIZONTAL = 'horizontal';
    const RADIOFORME_VERTICAL   = 'vertical';

    const RADIOPLACE_LEFT  = "left";
    const RADIOPLACE_RIGHT = "right";

    protected $const_radioType;
    protected $const_radioForme;
    protected $const_radioPlace;

    public function __construct($id)
    {
        parent::__construct($id, "oobject/odcontained/odradio/odradio.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function addOptions($value, $libel, $type = self::RADIOTYPE_DEFAULT, $state = self::STATE_ENABLE)
    {
        $properties = $this->getProperties();
        $types = $this->getRadioTypeConst();
        if (!in_array($type, $types)) $type = self::RADIOTYPE_DEFAULT;
        $state = ($state === true);

        if (!array_key_exists('options', $properties)) $properties['options'] = [];
        $item = [];
        $item['libel'] = $libel;
        $item['check'] = false;
        $item['type']  = $type;
        $item['state'] = $state;
        $item['value'] = $value;
        $properties['options'][$value] = $item;
        $this->setProperties($properties);
        return $this;
    }

    public function removeOption($value)
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties)) {
            $options = $properties['options'];
            if (array_key_exists($value, $options)) {
                unset($options[$value]);
                $properties['options'] = $options;
                $this->setProperties($properties);
                return $this;
            }
        }
        return false;
    }

    public function setOptions(array $options = null)
    {
        $properties = $this->getProperties();
        if (!empty($options)) {
            $properties['options'] = $options;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getOptions()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('options',$properties)) ? $properties['options'] : false);
    }

    public function check($value)
    {
        if ($value != null) {
            $properties = $this->getProperties();
            $options = $properties['options'];
            if (array_key_exists($value, $options)) {
                $this->uncheckAll();
                $options[$value]['check'] = self::RADIO_CHECK;
                $properties['options'] = $options;
                $this->setProperties($properties);
                return $this;
            }
        }
        return false;
    }

    public function uncheck($value)
    {
        if ($value != null) {
            $properties = $this->getProperties();
            $options = $properties['options'];
            if (array_key_exists($value, $options)) {
                if ($options[$value]['check'] == 'check') {
                    $options[$value]['check'] = self::RADIO_UNCHECK;
                    $this->setProperties($properties);
                    return $this;
                }
            }
        }
        return false;
    }

    public function uncheckAll()
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties)) {
            $options = $properties['options'];
            foreach ($options as $key => $option) {
                $option['check'] = false;
                $options[$key]   = $option;
            }
            $properties['options'] = $options;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getCheck($value)
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties)) {
            $options = $properties['options'];
            return ((array_key_exists($value, $options)) ? $options[$value]['check'] : false);
        }
        return false;
    }

    public function setLabel($label)
    {
        $label = (string)$label;
        $properties = $this->getProperties();
        $properties['label'] = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('label',$properties)) ? $properties['label'] : false);
    }

    public function evtClick($class, $method, $stopEvent = true)
    {
        $properties = $this->getProperties();
        if(!isset($properties['event'])) $properties['event'] = [];
        $properties['event']['click'] = [];
        $properties['event']['click']['class'] = $class;
        $properties['event']['click']['method'] = $method;
        $properties['event']['click']['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';
        $this->setProperties($properties);
        return $this;
    }

    public function disClick()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']['click'])) unset($properties['event']['click']);
        $this->setProperties($properties);
        return $this;
    }

    public function evtChange($class, $method, $stopEvent = true)
    {
        $properties = $this->getProperties();
        if(!isset($properties['event'])) $properties['event']= [];
        $properties['event']['change'] = [];
        $properties['event']['change']['class'] = $class;
        $properties['event']['change']['method'] = $method;
        $properties['event']['change']['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';
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

    public function setForme($forme = self::RADIOFORM_HORIZONTAL)
    {
        $formes = $this->getRadioFormeConst();
        if (!in_array($forme, $formes)) $forme = self::RADIOFORME_HORIZONTAL;
        $properties = $this->getProperties();
        $properties['forme'] = $forme;
        $this->setProperties($properties);
        return $this;
    }

    public function getForme()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('forme',$properties)) ? $properties['forme'] : false);
    }

    public function setPlacement($placement = self::RADIOPLACE_LEFT)
    {
        $placements = $this->getRadioPlaceConst();
        if (!in_array($placement, $placements)) $forme = self::RADIOPLACE_LEFT;
        $properties = $this->getProperties();
        $properties['place'] = $placement;
        $this->setProperties($properties);
        return $this;
    }

    public function getPlacement()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('place',$properties)) ? $properties['place'] : false);
    }


    protected function getRadioTypeConst()
    {
        $retour = [];
        if (empty($this->const_radioType)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'RADIOTYPE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_radioType = $retour;
        } else {
            $retour = $this->const_radioType;
        }
        return $retour;
    }

    protected function getRadioFormeConst()
    {
        $retour = [];
        if (empty($this->const_radioForme)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'RADIOFORME');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_radioForme = $retour;
        } else {
            $retour = $this->const_radioForme;
        }
        return $retour;
    }

    protected function getRadioPlaceConst()
    {
        $retour = [];
        if (empty($this->const_radioPlace)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'RADIOPLACE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_radioPlace = $retour;
        } else {
            $retour = $this->const_radioPlace;
        }
        return $retour;
    }

}