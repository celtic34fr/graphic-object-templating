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
 * addChild(OObject $child, $required = false) : methode surchargée ajout l'objet en enfant + obj->setForm(id form)
 * enaRequiredChild(OObject $child)
 * disRequiredChild(OObject $child)
 * setAction($action = self::ACTION_INIT)
 * getAction()
 * setFormDatas(array $datas) :
 * getFormDatas()         :
 * setValidMethod($obj, $method)
 * getValidMethod()
 * addSubmit($label, $nature, $class, $method, $stopevent = true)
 * clearSubmits()
 * setSubmits(array $submits)
 * getSubmits()
 * isValid()
 * getFieldsIdentifers(string $object = null)
 *
 * méthodes privées
 * getActionsContants()
 * getRequiredChildren()
 * propageRequire(ODContained $objet, OSContainer $Ochild, OSForm $form)
 * propageForm(OObject $objet)
 */
class OSForm extends OSContainer
{
    const ACTION_INIT   = 'init';
    const ACTION_RESET  = 'reset';
    const ACTION_SETVAL = 'setVal';

    public function __construct($id) {
        $parent = parent::__construct($id, 'oobject/oscontainer/osform/osform.config.php');
        $this->properties = $parent->properties;
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
    
    public function addChild(OObject $child, $required = false) {
        $properties = $this->getProperties();
        if ($child instanceof ODContained) {
            if (empty($properties['requireChildren'])) { $properties['requireChildren'] = []; }
            if ($required && !in_array($child->getId(), $properties['requireChildren'])) {
                $properties['requireChildren'][] = $child->getId();
            }
            $this->setProperties($properties);
            $properties = $this->propageForm($child);
        } elseif ( $child instanceof OSContainer) {
            $children = $child->getChildren();
            foreach ($children as $Ichild) {
                $properties = $this->propageForm($Ichild);
            }
        }
        $this->setProperties($properties);
        parent::addChild($child);
        return $this;
    }

    public function setRequeredChild(ODContained $objet)
    {
        if ($this->isChild($objet->getId())) {
            $properties = $this->getProperties();
            if (empty($properties['requireChildren'])) { $properties['requireChildren'] = []; }
            if (!in_array($objet->getId(), $properties['requireChildren'])) {
                $properties['requireChildren'][] = $objet->getId();
            }
            $this->setProperties($properties);
            $objet->setForm($this->getId());
            return $this;
        } else {
            // l'objet fait parti d'une sous partie du formulaire
            $children = $this->getChildren();
            foreach ($children as $child) {
                if ($child instanceof OSContainer) {
                    $this->propageRequire($objet, $child, $this);
                }
            }
        }
        return false;
    }

    public function rmRequeredChild(OObject $child)
    {
        if ($this->isChild($child->getId())) {
            $properties = $this->getProperties();
            $key = array_search($child->getId(), $properties['requireChildren']);
            if ($key !== false) {
                unset($properties['requireChildren'][$key]);
            }
            $this->setProperties($properties);
            $child->setForm($this->getId());
            return $this;
        }
        return false;
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

    /** @return mixed bool|array */
    public function getFormDatas()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['datas'])) ? $properties['datas'] : false) ;
    }

    public function setValidMethod($obj, $method)
    {
	if (is_object($obj)) { 
        $method     = (string) $method;
        $properties = $this->getProperties();
        $properties['validMethod'] = get_class($obj) .'§' . $method;
        $this->setProperties($properties);
	return $this;
	}
	return false;
    }

    public function getValidMethod()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['validMethod'])) ? $properties['validMethod'] : false) ;
    }

    public function addSubmit($label, $nature, $class, $method, $stopevent = true)
    {
        $properties = $this->getProperties();
        if (!array_key_exists('submits', $properties) || !is_array($properties['submits'])) {
            $properties['submits'] = [];
        }
        $numBtn = count($properties['submits']);
        $submit = new ODButton($this->getId().$numBtn);
        $submit->setLabel($label);
        $submit->evtClick($class, $method, $stopevent);
        $submit->setNature($nature);

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

    public function setResetLabel($label)
    {
        $label = (string) $label;
        /** @var ODButton $reset */
        $reset = OObject::buildObject($this->getId().'Reset');
        $reset->setLabel($label);
    }

    public function isValid($sm)
    {
        $valid = "";

        /* validation présence des champs requis */
        $requiredChildren = $this->getRequiredChildren();
        $datas            = $this->getFormDatas();
        if ($requiredChildren !== false) {
            $allRequired = true;
            foreach ($requiredChildren as $requiredChild) {
                if (!array_key_exists($requiredChild, $datas) || empty($datas[$requiredChild])) {
                    $allRequired = false;
                }
            }
            $valid = ($allRequired) ? '' : 'empty';
        }
        /* validation par la méthode callback (métier) de validation des données */
        if (empty($valid)) {
            $validMethod = $this->getValidMethod();
            if ($validMethod !== false) {
                $validMethod = explode('§', $validMethod);
                $object = new $validMethod[0];
                $valid = call_user_func_array(array($object, $validMethod[1]),
                    array($sm, $datas));
            }
        }
        return $valid;
    }

    public function getFieldsIdentifers(string $object = null)
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
			else {
			    $nFieldsIdentifers = $this->getFieldsIdentifers($obj->getId());
                foreach ($nFieldsIdentifers as $nFieldsIdentifer) {
                    array_push($fieldsIdentifers, $nFieldsIdentifer);
			    }
			}
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

    private function getRequiredChildren()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['requireChildren'])) ? $properties['requireChildren'] : false) ;
    }

    private function propageRequire(ODContained $objet, OSContainer $Ochild, OSForm $form)
    {
        if ($Ochild->isChild($objet->getId())) {
            $properties = $form->getProperties();
            if (empty($properties['requireChildren'])) { $properties['requireChildren'] = []; }
            if (!in_array($objet->getId(), $properties['requireChildren'])) {
                $properties['requireChildren'][] = $objet->getId();
            }
            $form->setProperties($properties);
            $ret = true;
        } else {
            $children = $Ochild->getChildren();
            foreach ($children as $child) {
                if ($child instanceof OSContainer) {
                    $ret = $this->propageRequire($objet, $child, $form);
                    if ($ret) { break; }
                }
            }
        }
        if (!isset($ret)) { $ret = false; }
        return $ret;
    }

    private function propageForm(OObject $objet)
    {
        $properties = $this->getProperties();
        if ($objet instanceof ODContained) {
            $objet->setForm($this->getId());
            if (empty($properties['childrenIdent']))   { $properties['childrenIdent'] = []; }
            if (!in_array($objet->getId(), $properties['childrenIdent'])) {
                $properties['childrenIdent'][] = $objet->getId();
            }
        } elseif ( $objet instanceof OSContainer) {
            $children = $objet->getChildren();
            foreach ($children as $child) {
                $properties = $this->propageForm($child);
            }
        }
        return $properties;
    }
}
