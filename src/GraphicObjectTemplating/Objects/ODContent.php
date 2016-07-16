<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 10:41
 */

namespace GraphicObjectTemplating\Objects;

/**
 * Class ODContent
 * @package GraphicObjectTemplating\Objects
 * 
 * setName
 * getName
 * setValue
 * getValue
 * setForm
 * getForm
 * convertValue
 * getConverted
 */
class ODContent extends OObject
{
    const ICON_SEARCH = "fa fa-search";
    const ICON_EDIT   = "fa fa-edit";
    const ICON_USER   = "fa fa-user";
    const ICON_DELETE = "fa fa-trash-o";

    protected $form;
    protected $name;
    protected $value;

    protected $const_icon;

    public function __construct($id, $adrProperties)
    {
        $properties  = include(__DIR__ ."/../../../view/graphic-object-templating/oobject/odcontent/odcontent.config.phtml");
        $parent      = parent::__construct($id, trim($adrProperties));
        $properties  = array_merge($parent->properties, $properties);
        $this->form  = $properties['form'];
        $this->name  = $properties['name'];
        $this->value = $properties['value'];
        return $this;
    }

    public function setName($name)
    {
        $name               = (string) $name;
        $properties         = $this->getProperties();
        $properties['name'] = $name;
        $this->name         = $name;
        $this->setProperties($properties);
        return $this;
    }

    public function getName() {
        if (!empty($this->name)) {
            $name       = $this->name;
        } else {
            $properties = $this->getProperties();
            $name       = (array_key_exists('name', $properties)) ? $properties['name'] : '';
            $this->name = $name;
        }
        return $name;
    }

    public function setValue($value)
    {
        $properties          = $this->getProperties();
        $properties['value'] = $value;
        $this->value         = $value;
        $this->setProperties($properties);
        return $this;
    }

    public function getValue()
    {
        if (!empty($this->value)) {
            $value = $this->value;
        } else {
            $properties  = $this->getProperties();
            $value       = (array_key_exists('value', $properties)) ? $properties['value'] : '';
            $this->value = $value;
        }
        return $value;
    }

    public function setForm($form)
    {
        $form               = (string) $form;
        $properties         = $this->getProperties();
        $properties['form'] = $form;
        $this->form         = $form;
        $this->setProperties($properties);
        return $this;
    }

    public function getForm() {
        if (!empty($this->form)) {
            $value = $this->form;
        } else {
            $properties = $this->getProperties();
            $value      = (array_key_exists('value', $properties)) ? $properties['value'] : '';
            $this->form = $value;
        }
        return $value;
    }

    public function convertValue($value) { return $this->setValue($value); }

    public function getConverted() { return $this->getValue(); }

    private function getIconsConst()
    {
        if (empty($this->const_icon)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TRIGGER');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_icon = $constants;
        } else {
            $constants = $this->const_icon;
        }
        return $constants;
    }
}
