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

    public function addOption($value, $libel, $selected = false, $enable = true)
    {
        $libel    = (string) $libel;
        $selected = (bool) $selected;
        $enable   = (bool) $enable;
        
        $properties = $this->getProperties();
        if (!array_key_exists('options', $properties)) $properties['options'] = [];
        $item = [];
        $item['libel'] = $libel;
        $item['select'] = $selected;
        $item['enable'] = $enable;
        $properties['options'][$value] = $item;
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

    public function enaOption($value)
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties) && !empty($properties['options'])) {
            $options = $properties['options'];
            if (array_key_exists($value, $options)) {
                $options[$value]['enable'] = true;
                $properties['options'] = $options;
                $this->setProperties($properties);
                return $this;
            }
        }
        return false;
    }

    public function disOption($value)
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties) && !empty($properties['options'])) {
            $options = $properties['options'];
            if (array_key_exists($value, $options)) {
                $options[$value]['enable'] = false;
                $properties['options'] = $options;
                $this->setProperties($properties);
                return $this;
            }
        }
        return false;
    }

    public function selOption($value)
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties) && !empty($properties['options'])) {
            $options = $properties['options'];
            if (array_key_exists($value, $options)) {
                $options[$value]['selected'] = true;
                $properties['options'] = $options;
                $this->setProperties($properties);
                return $this;
            }
        }
        return false;
    }

    public function unselOption($value)
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties) && !empty($properties['options'])) {
            $options = $properties['options'];
            if (array_key_exists($value, $options)) {
                $options[$value]['selected'] = false;
                $properties['options'] = $options;
                $this->setProperties($properties);
                return $this;
            }
        }
        return false;
    }

    public function setOptions(array $options)
    {
        /**
         * le controle de la structure de $options
         * -> cle : tableau (libel, selected, enable) pour chaque occurance
         */
        $top = true;
        foreach ($options as $option) {
            if (is_array($option)) {
                $top = $top && array_key_exists('libel', $option);
                $top = $top && array_key_exists('selected', $option);
                $top = $top && array_key_exists('enable', $option);
            }
        }
        /** réelle affectation du tableau si tout ok */
        if ($top) {
            $properties = $this->getProperties();
            $properties['options'] = $options;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getOptions()
    {
        $properties = $this->getProperties();
        return (array_key_exists('options', $properties) ? $properties['options'] : false);
    }

    public function enaMultiple()
    {
        $properties = $this->getProperties();
        $properties['multiple'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disMultiple()
    {
        $properties = $this->getProperties();
        $properties['multiple'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function enaSelect2()
    {
        $properties = $this->getProperties();
        $properties['select2'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disSelect2()
    {
        $properties = $this->getProperties();
        $properties['select2'] = false;
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
}