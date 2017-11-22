<?php

namespace GraphicObjectTemplating\OObjects;

use GraphicObjectTemplating\OObjects\OObject;

/**
 * Class ODConta    ined
 * @package GraphicObjectTemplating\OObjects
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
class ODContained extends OObject
{
    const ICON_SEARCH = "fa fa-search";
    const ICON_EDIT   = "fa fa-edit";
    const ICON_USER   = "fa fa-user";
    const ICON_DELETE = "fa fa-trash-o";

    protected $form;
    protected $name;
    protected $value;

    protected $const_icon;

    public function __construct($id, $adrProperties = null)
    {
        $path  = __DIR__ ;
        $path .= "/../../view/graphic-object-templating/oobjects/odcontained/odcontained.config.php";
        $properties = include($path);
        parent::__construct($id, trim($adrProperties));
        foreach ($this->properties as $key => $property) {
            $properties[$key] = $property;
        }
        $this->setProperties($properties);
        $this->setForm($properties['form']);
        $this->setName((empty($properties['name'])) ? $properties['id'] : $properties['name']);
        $this->setValue($properties['value']);
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
            $form = $this->form;
        } else {
            $properties = $this->getProperties();
            $form       = (array_key_exists('form', $properties)) ? $properties['form'] : '';
            $this->form = $form;
        }
        return $form;
    }

    public function convertValue($value) { return $this->setValue($value); }

    public function getConverted() { return $this->getValue(); }

    private function getIconsConst()
    {
        if (empty($this->const_icon)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'ICON');
                if ($pos === false) unset($constants[$key]);
            }
            $this->const_icon = $constants;
        } else {
            $constants = $this->const_icon;
        }
        return $constants;
    }
}
