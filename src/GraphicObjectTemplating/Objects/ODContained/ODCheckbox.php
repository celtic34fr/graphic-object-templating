<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;
use GraphicObjectTemplating\Objects\OObject;
use GraphicObjectTemplating\Service\GotServices;
use Zend\ServiceManager\ServiceManager;

/**
 * Class ODCheckbox
 * @package GraphicObjectTemplating\Objects\ODContained
 * 
 * addOption($value, $libel, $type = self::CHECKTYPE_DEFAULT, $state = self::STATE_ENABLE)
 * removeOption($value)
 * setOptions(array $options = null)
 * getOptions()
 * clearOptions()
 * check($value = null)
 * uncheck($value = null)
 * uncheckAll()
 * enaCheck($value = null)
 * disCheck($value = null)
 * getCheck($value = null)
 * getChecked()
 * setLabel($label)
 * getLabel()
 * evtChange($class, $method, $stopEvent = true)
 * disChange()
 * setForme($forme = self::CHECKFORM_HORIZONTAL)
 * getForme()
 * setPlacement($placement = self::CHECKPLACE_LEFT)
 * getPlacement()
 * saveState($sm, $params)
 */
class ODCheckbox extends ODContained
{
    const CHECKBOX_CHECK   = "check";
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
        parent::__construct($id, "oobject/odcontained/odcheckbox/odcheckbox.config.php");
        $this->id = $id;
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
        return $this;
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
        $state = ($state === true);

        if (!array_key_exists('options', $properties)) $properties['options'] = [];
        $item = [];
        $item['libel'] = $libel;
        $item['check'] = self::CHECKBOX_UNCHECK;
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

    public function clearOptions()
    {
        $properties = $this->getProperties();
        $properties['options'] = [];
        $this->setProperties($properties);
        return $this;
    }

    public function getOptions()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('options',$properties)) ? $properties['options'] : false);
    }

    public function check($value = null)
    {
        if ($value != null) {
            $properties = $this->getProperties();
            if (array_key_exists('options', $properties)) {
                $options = $properties['options'];
                if (array_key_exists($value, $options)) {
                    $options[$value]['check'] = self::CHECKBOX_CHECK;
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
        if ($value != null) {
            $properties = $this->getProperties();
            if (array_key_exists('options', $properties)) {
                $options = $properties['options'];
                if (array_key_exists($value, $options)) {
                    $options[$value]['check'] = self::CHECKBOX_UNCHECK;
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

    public function enaCheck($value = null)
    {
        if ($value != null) {
            $properties = $this->getProperties();
            if (array_key_exists('options', $properties)) {
                $options = $properties['options'];
                if (array_key_exists($value, $options)) {
                    $options[$value]['state'] = self::STATE_ENABLE;
                    $properties['options'] = $options;
                    $this->setProperties($properties);
                    return $this;
                }
            }
        }
        return false;
    }

    public function disCheck($value = null)
    {
        if ($value != null) {
            $properties = $this->getProperties();
            if (array_key_exists('options', $properties)) {
                $options = $properties['options'];
                if (array_key_exists($value, $options)) {
                    $options[$value]['state'] = self::STATE_DISABLE;
                    $properties['options'] = $options;
                    $this->setProperties($properties);
                    return $this;
                }
            }
        }
        return false;
    }

    public function getCheck($value = null)
    {
        $properties = $this->getProperties();
        if (!empty($value) && array_key_exists('options', $properties)) {
            $options = $properties['options'];
            return ((array_key_exists($value, $options)) ? $options[$value]['check'] : self::CHECKBOX_UNCHECK);
        }  elseif (empty($value) && !empty($properties['options'])) {
            $options = $properties['options'];
            $label   = $properties['label'];
            return ((array_key_exists($label, $options)) ? $options[$label]['check'] : self::CHECKBOX_UNCHECK);
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

    public function dispatchEvents(ServiceManager $sm, $params)
    {
        /** @var GotServices $gs */
        $gs = $sm->get('graphic.object.templating.services');
        $ret    = [];
        /** @var ODCheckbox $objet */
        $objet  = OObject::buildObject($params['id']);
        $values = $params['value'];
        $values = explode('$', $values);
        // sauvegarde de l'état des cases à cocher
        if (!is_array($values)) { $values = [$values]; }
        $objet->uncheckAll();
        foreach ($values as $value) {
            $objet->check($value);
        }
        $item           = [];
        $item['id']     = $objet->getId();
        $item['mode']   = 'update';
        $item['html']   = $gs->render($objet);
        $ret[]          = $item;

        // validation et appel de la callback si existe
        $event      = $this->getEvent('change');
        $class      = (array_key_exists('class', $event)) ? $event['class'] : "";
        $method     = (array_key_exists('method', $event)) ? $event['method'] : "";
        $stopEvt    = (array_key_exists('stopEvent', $event)) ? $event['stopEvent'] : "NON";
        if (!empty($class) && !empty($method)) {
            $callObj = new $class();
            $retCallObj = call_user_func_array([$callObj, $method], [$sm, $params]);
            foreach ($retCallObj as $item) {
                array_push($ret, $item);
            }
        }
        return [$ret];
    }


    private function getCheckboxConst()
    {
        $retour = [];
        if (empty($this->const_checkbox)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'CHECKBOX');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_checkbox = $retour;
        } else {
            $retour = $this->const_checkbox;
        }
        return $retour;
    }

    private function getCheckTypeConst()
    {
        $retour = [];
        if (empty($this->const_checkType)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'CHECKTYPE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_checkType = $retour;
        } else {
            $retour = $this->const_checkType;
        }
        return $retour;
    }

    private function getCheckFormeConst()
    {
        $retour = [];
        if (empty($this->const_checkForme)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'CHECKFORME');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_checkForme = $retour;
        } else {
            $retour = $this->const_checkForme;
        }
        return $retour;
    }

    private function getCheckPlaceConst()
    {
        $retour = [];
        if (empty($this->const_checkPlace)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'CHECKPLACE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_checkPlace = $retour;
        } else {
            $retour = $this->const_checkPlace;
        }
        return $retour;
    }

}