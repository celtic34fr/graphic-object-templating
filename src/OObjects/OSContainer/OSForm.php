<?php

namespace GraphicObjectTemplating\OObjects\OSContainer;

use GraphicObjectTemplating\OObjects\ODContained;
use GraphicObjectTemplating\OObjects\ODContained\ODButton;
use GraphicObjectTemplating\OObjects\OEDContained;
use GraphicObjectTemplating\OObjects\OESContainer;
use GraphicObjectTemplating\OObjects\OObject;
use GraphicObjectTemplating\OObjects\OSContainer;

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
 * setSubmit($name, $label = null, $nature = null, $class =null, $method = null,
 *      $stopevent = true)
 * clearSubmits()
 * setSubmits(array $submits)
 * getSubmits()
 * setReset($label, $nature = null, class=null)
 * setResetLabel($label)
 * setResetNature($nature = null)
 * showReset()
 * hideReset()
 * getReset()
 * isValid()
 * getFieldsIdentifers(string $object = null)
 * clearForm()
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

    const IN_FORM       = true;
    const OUT_FORM      = false;

    public function __construct($id) {
        $parent = parent::__construct($id, 'oobjects/oscontainer/osform/osform.config.php');
        $this->properties = $parent->properties;
        $this->id = $id;
        $this->setDisplay();
        $properties = $this->getProperties();

        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) { $this->setWidthBT(12); }
        $this->setForm($id);

        if (array_key_exists('reset', $properties) && empty($properties['reset'])) {
            $reset = new ODButton($this->getId().'Reset');
            $reset->setLabel('Reset');
            $reset->setForm($this->getId());
            $reset->setNature(ODButton::NATURE_WARNING);
            $reset->setType(ODButton::BTNTYPE_RESET);
            $reset->setWidthBT('O2:W10');
            $properties['reset'] = $reset;
        }
        $this->setProperties($properties);
        $this->enable();
        return $this;
    }
    
    public function addChild(OObject $child, $inForm = false, $mode = self::MODE_LAST, $param = null) {
        parent::addChild($child, $mode, $param);
        if ($inForm) {
            if ($child instanceof ODContained || $child instanceof OEDContained) {
                $child->setForm($this->getId());
            } elseif ($child instanceof OSContainer || $child instanceof OESContainer) {
                $childen = $child->getChildren();
                foreach ($childen as $iChild) {
                    $this->propageForm($iChild, $this->getId());
                }
            }
        }
        return $this;
    }

    public function setRequeredChild(OObject $objet)
    {
        if ($objet instanceof ODContained || $objet instanceof OEDContained) {
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

    public function addSubmit($name, $label, $nature, $class, $method, $stopevent = true)
    {
        $name   = (string) $name;
        $label  = (string) $label;
        $reset  = OObject::buildObject($this->getId().'Reset');
        
        $properties = $this->getProperties();
        if (!array_key_exists('submits', $properties) || !is_array($properties['submits'])) {
            $properties['submits'] = [];
        }
        
        if (!array_key_exists($name, $properties['submits'])) {
            $numBtn = count($properties['submits']);
            $submit = new ODButton($this->getId().'Btn'.$numBtn);
            $submit->setLabel($label);
            $submit->evtClick($class, $method, $stopevent);
            $submit->setNature($nature);
            $submit->setType(ODButton::BTNTYPE_SUBMIT);
            $submit->setForm($this->getId());

            if ($reset->getDisplay() !== OObject::DISPLAY_NONE) {
                $widthBT = 10 / (2 + $numBtn) - 1;
                $reset->setWidthBT('O2:W'.(int)$widthBT);
            } else {
                $widthBT = 10 / (1 + $numBtn) - 1;                
            }
            $submit->setWidthBT('O1:W'.(int)$widthBT);

            $properties['submits'][$name] = $submit->getId();
            
            foreach ($properties['submits'] as $idSubmit) {
                $subBtn = OObject::buildObject($idSubmit);
                $subBtn->setWidthBT('O1:W'.(int)$widthBT);
            }
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function setSubmit($name, $label = null, $nature = null, $class =null, 
            $method = null, $stopevent = true)
    {
        $properties = $this->getProperties();
        if (array_key_exists('submits', $properties)) {
            $submits = $properties['submits'];
            if (array_key_exists($name, $submits)) {
                /** @var ODButton $object **/
                $object = OObject::buildObject($submits[$name]);
                if (!empty($label))     { $object->setLabel($label); }
                if (!empty($nature))    { $object->setNature($nature); }
                if (!empty($class) && !empty($method)) {
                    $object->evtClick($class, $method, $stopevent);
                }
                return $this;
            }
        }
        return false;
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

    public function getSubmit($name)
    {
        $properties = $this->getProperties();
        if (array_key_exists('submits', $properties)) {
            $submits = $properties['submits'];
            if (array_key_exists($name, $submits)) {
                $object = OObject::buildObject($submits[$name]);
                $click  = $object->getEvent('click');
                return [
                    'name'=>$name, 'label'=>$object->getLabel(), 'nature'=>$object->getNature(),
                        'class'=>$click['class'], 'method'=>$click['method'], 'stopEvent'=>$click['stopEvent']
                ];
            }
        }
        return false;
    }

    public function setReset($label = null, $nature = null, $class = null)
    {
        $label = (string) $label;
        $nature = (string) $nature;
        /** @var ODButton $reset */
        $reset = OObject::buildObject($this->getId().'Reset');
        if (!empty($label)) { $reset->setLabel($label); }
        $reset->setNature($nature);
        if (!empty($class)) { $reset->addClass($class); }
    }

    public function setResetLabel($label)
    {
        $label = (string) $label;
        /** @var ODButton $reset */
        $reset = OObject::buildObject($this->getId().'Reset');
        $reset->setLabel($label);
    }

    public function setResetNature($nature = null)
    {
        $nature = (string) $nature;
        /** @var ODButton $reset */
        $reset = OObject::buildObject($this->getId().'Reset');
        $reset->setNature($nature);
    }
    
    public function showReset() {
        /** @var ODButton $reset */
        $reset = OObject::buildObject($this->getId().'Reset');
        $reset->setDisplay(OObject::DISPLAY_INBLOCK);
    }
    
    public function hideReset() {
        /** @var ODButton $reset */
        $reset = OObject::buildObject($this->getId().'Reset');
        $reset->setDisplay(OObject::DISPLAY_NONE);
    }
    
    public function getResetVisibility() {
        /** @var ODButton $reset */
        $reset = OObject::buildObject($this->getId().'Reset');
        return $reset->getDisplay();
    }

    public function getReset()
    {
        return OObject::buildObject($this->getId().'Reset');
    }

    public function isValid($sm)
    {
        $valid = true;

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
            $valid = ($allRequired) ? '' : 'ERROR|empty';
        }
        /* validation par la méthode callback (métier) de validation des données devant retourner true ou false */
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

    public function clearForm()
    {
        $fields = $this->getFieldsIdentifers();
        foreach ($fields as $field) {
            /** @var ODContained $objet */
            $objet = OObject::buildObject($field);
            $objet->setValue(null);
        }
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

    private function propageRequire(OObject $objet, OObject $Ochild, OSForm $form)
    {
        $ret = false;
        if ($objet instanceof ODContained || $objet instanceof OEDContained) {
            if ($Ochild instanceof OSContainer || $Ochild instanceof OESContainer) {
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
            }
        }
        return $ret;
    }

    public function propageForm(OObject $objet, $idForm = null)
    {
        if (empty($idForm)) { $idForm = $this->getId(); }
        if ($objet instanceof OSContainer || $objet instanceof OESContainer) {
            $children = $objet->getChildren();
            foreach ($children as $child) {
                $this->propageForm($child, $idForm);
            }
        } else {
            $objet->setForm($idForm);
        }
    }
}