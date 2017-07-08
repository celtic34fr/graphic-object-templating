<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODSelect
 * @package GraphicObjectTemplating\Objects\ODContained
 * 
 * addOption($value, $libel, $selected = false, $enable = true)
 * removeOption($value)
 * clearOptions()
 * enaOption($value)
 * disOption($value)
 * selOption($value)
 * unselOption($value)
 * setOptions(array $options)
 * getOptions()
 * enaMultiple($number)
 * disMultiple()
 * enaSelect2()
 * disSelect2()
 * setPlaceholder($placeholder)
 * getPlaceholder()
 * unselectAll()
 * setLabel($label)
 * getLabel()
 * showSearchBox()
 * hideSearchBox()
 * setLanguage($language)
 * getLanguage()
 * evtClick(callback)
 * disClick()
 * evtChange(callback)
 * disChange()
 * setLabelWidthBT($widthBT)
 * getLabelWidthBT()
 */
class ODSelect extends ODContained
{
    public function __construct($id) {
        parent::__construct($id, "oobject/odcontained/odselect/odselect.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
        return $this;
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
                if ($properties['multiple'] === false) $this->unselectAll();
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

    public function enaMultiple($number)
    {
        /** $number nombre de sélection valable pour mode select2 */
        $number = (int) $number; 
        $properties = $this->getProperties();
        $properties['multiple'] = ($number == 0) ? true : $number;
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

    public function unselectAll()
    {
        $properties = $this->getProperties();
        if (array_key_exists('options', $properties) && !empty($properties['options'])) {
            $options = $properties['options'];
            foreach ($options as $key => $option) {
                $options[$key]['selected'] = false;
            }
            $properties['options'] = $options;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function setLabel($label)
    {
        $label = (string) $label;
        $properties = $this->getProperties();
        $properties['label'] = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties = $this->getProperties();
        return (array_key_exists('label', $properties) ? $properties['label'] : false);
    }

    public function showSearchBox()
    {
        /* paramètre pour mode select2 visialise la boite de recherche (pour présaisie option à sélectionner) */
        $properties = $this->getProperties();
        if (!array_key_exists('paramsSelect2',$properties)) $properties['paramsSelect2'] = [];
        $properties['paramsSelect2']['searchBox'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function hideSearchBox()
    {
        /* paramètre pour mode select2 cache la boite de recherche (pour présaisie option à sélectionner) */
        $properties = $this->getProperties();
        if (!array_key_exists('paramsSelect2',$properties)) $properties['paramsSelect2'] = [];
        $properties['paramsSelect2']['searchBox'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function setLanguage($language)
    {
        $language = (string) $language;
        if (strlen($language) == 2) {
            $fileToSearch = __DIR__ ."/../../../../public/objets/odcontained/odselect/js/i18n/".$language.".js";
            if (file_exists($fileToSearch)) {
                $properties = $this->getProperties();
                if (!array_key_exists('paramsSelect2',$properties)) $properties['paramsSelect2'] = [];
                $properties['paramsSelect2']['language'] = $language;
                $this->setProperties($properties);
                return $this;
            }
        }
        return false;
    }

    public function getLanguage()
    {
        $properties = $this->getProperties();
        if (array_key_exists('paramsSelect2', $properties)) {
            $paramsSelect2 = $properties['paramsSelect2'];
            return (array_key_exists('language', $paramsSelect2) ? $paramsSelect2['language'] : false);
        }
        return false;
    }

    public function evtClick($callback)
    {
        $callback = (string) $callback;
        $properties             = $this->getProperties();
        if(!isset($properties['event'])) $properties['event'] = [];
        if(!is_array($properties['event'])) $properties['event'] = [];
        $properties['event']['click'] = $callback;

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