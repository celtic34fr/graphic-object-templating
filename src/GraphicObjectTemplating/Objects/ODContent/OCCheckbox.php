<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 09/07/16
 * Time: 19:34
 */

namespace GraphicObjectTemplating\Objects\ODContent;

use GraphicObjectTemplating\Objects\ODContent;

/**
 * Class OCCheckbox
 * @package GraphicObjectTemplating\Objects\ODContent
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
 */
class OCCheckbox extends ODContent
{
    const CHECKBOX_CHECK = "check";
    const CHECKBOX_UNCHECK = "uncheck";

    public function __construct($id)
    {
        parent::__construct($id, "oobject/odcontent/occheckbox/occheckbox.config.phtml");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function addOptions($value, $libel)
    {
        $properties = $this->getProperties();
        $label      = (array_key_exists('label', $properties)) ? $properties['label'] : "";
        if (array_key_exists('options',$properties) && sizeof($properties['options']) == 1 && array_key_exists($label, $properties['options'])) {
            $properties['options'] = [];
        }

        if (!array_key_exists('options', $properties)) $properties['options'] = [];
        $item = [];
        $item['libel'] = $libel;
        $item['check'] = false;
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

        if (empty($properties['options'])
            || sizeof($properties['options']) == 1 && array_key_exists($oldLabel, $properties['options'])) {
            $options = [];
            $item = [];
            $item['libel'] = $label;
            $item['check'] = self::CHECKBOX_UNCHECK;
            $options[$label] = $item;
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
    }}