<?php

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
 * evtFocus
 * disFocus
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
        $parent = parent::__construct($id, "oobject/odcontained/odtextarea/odtextarea.config.php");
        $this->properties = $parent->properties;
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
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

    public function evtFocus($class, $method, $stopEvent = true)
    {
        $properties = $this->getProperties();
        if(!isset($properties['event'])) $properties['event']= [];
        $properties['event']['focus'] = [];
        $properties['event']['focus']['class'] = $class;
        $properties['event']['focus']['method'] = $method;
        $properties['event']['focus']['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';
        $this->setProperties($properties);
        return $this;
    }

    public function disFocus()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']['focus'])) unset($properties['event']['focus']);
        $this->setProperties($properties);
        return $this;
    }

    public function evtBlur($class, $method, $stopEvent = true)
    {
        $properties = $this->getProperties();
        if(!isset($properties['event'])) $properties['event']= [];
        $properties['event']['blur'] = [];
        $properties['event']['blur']['class'] = $class;
        $properties['event']['blur']['method'] = $method;
        $properties['event']['blur']['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';
        $this->setProperties($properties);
        return $this;
    }

    public function disBlur()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']['blur'])) unset($properties['event']['blur']);
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

    public function setLabelWidthBT($widthBT)
    {
        $lxs = 0; $ixs = 0;
        $lsm = 0; $ism = 0;
        $lmd = 0; $imd = 0;
        $llg = 0; $ilg = 0;

        switch (true) {
            case (is_numeric($widthBT)):
                $lxs = $widthBT; $ixs = 12 - $widthBT;
                $lsm = $widthBT; $ism = 12 - $widthBT;
                $lmd = $widthBT; $imd = 12 - $widthBT;
                $llg = $widthBT; $ilg = 12 - $widthBT;
                break;
            default:
                /** widthBT chaîne de caractères */
                $widthBT = explode(":", $widthBT);
                foreach ($widthBT as $item) {
                    $key = strtoupper(substr($item, 0, 2));
                    switch ($key) {
                        case "WX" : $lxs = (int)substr($item,2); break;
                        case "WS" : $lsm = (int)substr($item,2); break;
                        case "WM" : $lmd = (int)substr($item,2); break;
                        case "WL" : $llg = (int)substr($item,2); break;
                        default:
                            if (substr($key,0,1) == "W") {
                                $wxs = (int)substr($item,1);
                                $wsm = (int)substr($item,1);
                                $wmd = (int)substr($item,1);
                                $wlg = (int)substr($item,1);
                            }
                    }
                }
                $ixs = 12 - $lxs;
                $ism = 12 - $lsm;
                $imd = 12 - $lmd;
                $ilg = 12 - $llg;
                break;
        }
        $properties = $this->getProperties();
        $properties['labelWidthBT']['lxs'] = $lxs;
        $properties['labelWidthBT']['lsm'] = $lsm;
        $properties['labelWidthBT']['lmd'] = $lmd;
        $properties['labelWidthBT']['llg'] = $llg;
        $properties['labelWidthBT']['ixs'] = $ixs;
        $properties['labelWidthBT']['ism'] = $ism;
        $properties['labelWidthBT']['imd'] = $imd;
        $properties['labelWidthBT']['ilg'] = $ilg;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabelWidthBT()
    {
        $properties                = $this->getProperties();
        return ((!empty($properties['labelWidthBT'])) ? $properties['labelWidthBT'] : false) ;
    }

}