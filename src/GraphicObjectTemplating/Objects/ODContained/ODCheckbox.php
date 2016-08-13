<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 09/07/16
 * Time: 19:34
 */

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODCheckbox
 * @package GraphicObjectTemplating\Objects\ODContained
 * 
 * addOption
 * removeOption
 * setOptions
 * getOptions
 * check
 * uncheck
 * uncheckAll
 * getCheck
 * setLabel
 * getLabel
 * evtChange
 * disChange
 * setForme
 * getForme
 * setPlacement
 * getPlacement
 */
class ODCheckbox extends ODContained
{
    const CHECKBOX_CHECK = "check";
    const CHECKBOX_UNCHECK = "uncheck";

    const CHECKTYPE_DEFAULT = "checkbox";
    const CHECKTYPE_PRIMARY = "checkbox checkbox-primary";
    const CHECKTYPE_SUCCESS = "checkbox checkbox-success";
    const CHECKTYPE_INFO    = "checkbox checkbox-info";
    const CHECKTYPE_WARNING = "checkbox checkbox-warning";
    const CHECKTYPE_DANGER  = "checkbox checkbox-danger";

    const CHECKFORME_HORIZONTAL = 'horizontal';
    const CHECKFORME_VERTICAL   = 'vertical';

    const CHECKPLACE_LEFT  = "left";
    const CHECKPLACE_RIGHT = "right";

    protected $const_checkbox;
    protected $const_checkType;
    protected $const_checkForme;
    protected $const_checkPlace;

    public function __construct($id)
    {
        parent::__construct($id, "oobject/odcontained/odcheckbox/odcheckbox.config.phtml");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function addOptions($value, $libel, $type = self::CHECKTYPE_DEFAULT, $state = self::STATE_ENABLE)
    {
        $properties = $this->getProperties();
        $label      = (array_key_exists('label', $properties)) ? $properties['label'] : "";
        if (array_key_exists('options',$properties) && sizeof($properties['options']) == 1 && $label == $properties['options'][0]['libel']) {
            $properties['options'] = [];
        }
        $types = $this->getCheckTypeConst();
        if (!in_array($type, $types)) $type = self::CHECKTYPE_DEFAULT;
        $state = $state && true;

        if (!array_key_exists('options', $properties)) $properties['options'] = [];
        $item = [];
        $item['libel'] = $libel;
        $item['check'] = false;
        $item['type']  = $type;
        $item['state'] = $state;
        $item['value'] = $value;
        $properties['options'][] = $item;
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

    public function check($value = null)
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties)) {
            $options = $properties['options'];
            if (!empty($value) && array_key_exists($value, $options)) {
                $options[$value]['check'] = self::CHECKBOX_CHECK;
                $properties['options'] = $options;
                $this->setProperties($properties);
                return $this;
            } elseif (empty($value)) {
                $options = $properties['options'];
                if (empty($options) || array_key_exists($properties['label'], $options)) {
                    $options[$properties['label']] = self::CHECKBOX_CHECK;
                    $properties['options'] = $options;
                    $this->setProperties($properties);
                    return $this;
                }
            }
        }
        return false;
    }

    public function uncheck($value = null)
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties)) {
            $options = $properties['options'];
            if (!empty($value) && array_key_exists($value, $options)) {
                $options[$value]['check'] = self::CHECKBOX_UNCHECK;
                $properties['options'] = $options;
                $this->setProperties($properties);
                return $this;
            } elseif (empty($value)) {
                $options = $properties['options'];
                if (empty($options) || array_key_exists($properties['label'], $options)) {
                    $options[$properties['label']] = self::CHECKBOX_UNCHECK;
                    $properties['options'] = $options;
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
                $option['check'] = self::CHECKBOX_UNCHECK;
                $options[$key]   = $option;
            }
            $properties['options'] = $options;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }
    
    public function getCheck($value = null)
    {
        $properties = $this->getProperties();
        if (!empty($value) && array_key_exists('options', $properties)) {
            $options = $properties['options'];
            return ((array_key_exists($value, $options)) ? $options[$value]['check'] : false);
        }  elseif (empty($value) && !empty($properties['options'])) {
            $options = $properties['options'];
            $label   = $properties['label'];
            return ((array_key_exists($label, $options)) ? $options[$label]['check'] : false);
        }
        return false;
    }

    public function getChecked()
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties)) {
            $checked = [];
            foreach ($properties['options'] as $value => $option) {
                if ($option['check'] == self::CHECKBOX_CHECK) {
                    $checked[] = $value;
                }
            }
            return $checked;
        }
        return false;
    }

    public function setLabel($label)
    {
        $label = (string)$label;
        $properties = $this->getProperties();
        $oldLabel   = (array_key_exists('label',$properties)) ? $properties['label'] : "";
        $properties['label'] = $label;

        if ((!empty($oldLabel) && sizeof($properties['options']) == 1 && $properties['options'][0]['libel'] == $oldLabel)
            || (empty($properties['options']))) {
            $options = [];
            $item = [];
            $item['libel'] = $label;
            $item['check'] = self::CHECKBOX_UNCHECK;
            $item['type']  = self::CHECKTYPE_DEFAULT;
            $item['state'] = self::STATE_ENABLE;
            $item['value'] = "";
            $options[0] = $item;
            $properties['options'] = $options;
        }

        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('label',$properties)) ? $properties['label'] : false);
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

    public function setForme($forme = self::CHECKFORM_HORIZONTAL)
    {
        $formes = $this->getCheckFormeConst();
        if (!in_array($forme, $formes)) $forme = self::CHECKFORME_HORIZONTAL;
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

    public function setPlacement($placement = self::CHECKPLACE_LEFT)
    {
        $placements = $this->getCheckPlaceConst();
        if (!in_array($placement, $placements)) $forme = self::CHECKPLACE_LEFT;
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


    protected function getCheckboxConst()
    {
        if (empty($this->const_nature)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'CHECKBOX');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_checkbox = $constants;
        } else {
            $constants = $this->const_checkbox;
        }
        return $constants;
    }

    protected function getCheckTypeConst()
    {
        if (empty($this->const_nature)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'CHECKTYPE');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_checkType = $constants;
        } else {
            $constants = $this->const_checkType;
        }
        return $constants;
    }

    protected function getCheckFormeConst()
    {
        if (empty($this->const_nature)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'CHECKFORME');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_checkForme = $constants;
        } else {
            $constants = $this->const_checkForme;
        }
        return $constants;
    }

    protected function getCheckPlaceConst()
    {
        if (empty($this->const_nature)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'CHECKPLACE');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_checkPlace = $constants;
        } else {
            $constants = $this->const_checkPlace;
        }
        return $constants;
    }

}