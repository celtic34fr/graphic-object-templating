<?php

namespace GraphicObjectTemplating\Objects\OSContainer;

use GraphicObjectTemplating\Objects\ODContained;
use GraphicObjectTemplating\Objects\ODContained\ODButton;
use GraphicObjectTemplating\Objects\OObject;
use GraphicObjectTemplating\Objects\OSContainer;
use Zend\InputFilter\InputFilter;
use Zend\Session\Container;

/**
 * Class OSForm
 * @package Application\Objects\OSContainer
 *
 * addChild             : methode surchargée ajout l'objet en enfant + obj->setForm(id form)
 * getFormDatas         :
 * setFormDatas($datas) :
 * setValidMethod($obj, $method)
 * getValidMethod()
 * isValid()
 * addSubmit($label, $nature, $class, $method)
 */
class OSForm extends OSContainer
{
    const ACTION_INIT   = 'init';
    const ACTION_RESET  = 'reset';
    const ACTION_SETVAL = 'setVal';

    public function __construct($id) {
        $obj = OObject::buildObject($id);
        if ($obj === FALSE) {
        parent::__construct($id, 'oobject/oscontainer/osform/osform.config.phtml');
        } else {
            $this->setProperties($obj->getProperties());
        }
        $this->id = $id;
        $this->setDisplay();
        
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) { $this->setWidthBT(12); }
        $this->setForm($id);

        $reset = new ODButton($this->getId().'Reset');
        $reset->setLabel('Reset');
        $reset->setForm($this->getId());
        $reset->setNature(ODButton::NATURE_WARNING);
        $reset->setType(ODButton::BTNTYPE_RESET);
        $reset->setWidthBT('O2:W10');
        $properties = $this->getProperties();
        $properties['reset'] = $reset;
        $this->setProperties($properties);
    }
    
    public function addChild(OObject $child) {
        if ($child instanceof ODContained) {
            $child->setForm($this->getId());
            $properties = $this->getProperties();
            if (empty($properties['childrenIdent'])) { $properties['childrenIdent'] = []; }
            $properties['childrenIdent'][] = $child->getId();
            $this->setProperties($properties);
        }
        parent::addChild($child);
        return $this;
    }

    public function setAction($action = self::ACTION_INIT)
    {
        $actions = $this->getActionsContants();
        if (!in_array($action, $actions)) $action = self::ACTION_INIT;

        $properties = $this->getProperties();
        $properties['action'] = $action;
        $this->setProperties($properties);
        return $this;
    }

    public function getAction()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['action'])) ? $properties['action'] : false) ;
    }

    public function setFormDatas(array $datas)
    {
		$fieldsIdentifers = $this->getFieldsIdentifers();
		$topFound         = true;
		foreach($datas as $fieldIdentifer => $value) {
		    if (!in_array($fieldIdentifer, $fieldsIdentifers, true)) {
		        $topFound = false;
		        break;
            }
		}
		if ($topFound) {
			$properties = $this->getProperties();
			$properties['datas'] = $datas;
			$this->setProperties($properties);
			return $this;
		}
		return false;
    }

    public function getFormDatas()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['datas'])) ? $properties['datas'] : false) ;
    }

    public function setValidMethod(OObject $obj, $method)
    {
        $method     = (string) $method;
        $properties = $this->getProperties();
        $properties['validMethod'] = get_class($obj) .'§' . $method;
        $this->setProperties($properties);
    }

    public function getValidMethod()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['validMethod'])) ? $properties['validMethod'] : false) ;
    }

    public function setInputFilter(InputFilter $inputFilter)
    {
        $properties = $this->getProperties();
        $properties['inputFilter'] = $inputFilter;
        $this->setProperties($properties);
        return $this;
    }

    public function getInputFilter()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['inputFilter'])) ? $properties['inputFilter'] : false) ;
    }

    public function addSubmit($label, $nature, $class, $method, $stopevent = true)
    {
        $submit = new ODButton('submit');
        $submit->setLabel($label);
        $submit->evtClick($class, $method, $stopevent);
        $submit->setNature($nature);

        $properties = $this->getProperties();
        if (!array_key_exists('submits', $properties) || !is_array($properties['submits'])) {
            $properties['submits'] = [];
        }
        $numBtn = count($properties['submits']);
        $submit->setId($this->getId().$numBtn);
        $submit->setForm($this->getId());
        $widthBT = 10 / (2 + $numBtn) - 1;
        $submit->setWidthBT('O1:W'.(int)$widthBT);
        $reset   = OObject::buildObject($this->getId().'Reset');
        $reset->setWidthBT('O2:W'.(int)$widthBT);

        $properties['submits'][] = $submit->getId();
        $this->setProperties($properties);
        return $this;
    }

    public function clearSubmits()
    {
        $properties = $this->getProperties();
        $properties['submits'] = [];
        $this->setProperties($properties);
        return $this;
    }

    public function setSubmits(array $submits)
    {
        $properties = $this->getProperties();
        $properties['submits'] = [];
        foreach ($submits as $loop=>$submit) {
            $btnSubmit = new ODButton($this->getId().$loop);
            $btnSubmit->setLabel($submit['label']);
            $btnSubmit->setForm($this->getId());
            $btnSubmit->setNature(ODButton::NATURE_SUCCESS);
            $btnSubmit->setType(ODButton::BTNTYPE_SUBMIT);
            $btnSubmit->evtClick($submit['class'], $submit['method'], $submit['stopevent']);
            $properties['submits'][] = $btnSubmit->getId();
        }
        $this->setProperties($properties);
        return $this;
    }

    public function getSubmits()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['submits'])) ? $properties['submits'] : false) ;
    }

    public function isValid()
    {
        $valid = true;

        $validMethod = $this->getValidMethod();
        if ($validMethod !== false) {
            $validMethod = explode('§', $validMethod);
            $object = new $validMethod[0];
            $result = call_user_func_array(array($object, $validMethod[1]),
                array(
                    $this->getFormDatas()
                ));
            $valid = $valid && $result;
        }

        $inputFilter = $this->getInputFilter();
        if ($inputFilter !== false && $valid !== false) {
            $result = true;
            $valid = $valid && $result;
        }
        return $valid;
    }

    public function getFieldsIdentifers($object = null)
    {
		$fieldsIdentifers = [];
		if ($object === null) {
			$objet = $this;
		} else {
			$objet = OObject::buildObject($object);
		}
		$properties = $objet->getProperties();
		$children = $properties['children'];
		foreach( $children as $childId => $childVal) {
		    $obj = OObject::buildObject($childId);
			if ( $obj->getTypeObj() === 'odcontained') { $fieldsIdentifers[] = $childId; }
			else { $fieldsIdentifers = array_merge($fieldsIdentifers, $this->getFieldsIdentifers($obj)); }
		}
		return $fieldsIdentifers;
    }

    /*
     * méthode interne à la classe OObject
     */

    private function getActionsContants()
    {
        $retour = [];
        if (empty($this->const_mesAction)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'ACTION');
                if ($pos !== false) { $retour[$key] = $constant; }
            }
            $this->const_mesAction = $retour;
        } else {
            $retour = $this->const_mesAction;
        }
        return $retour;
    }
}
