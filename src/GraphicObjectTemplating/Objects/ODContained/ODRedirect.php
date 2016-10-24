<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 24/10/16
 * Time: 00:02
 */

namespace GraphicObjectTemplating\Objects\ODContained;


use GraphicObjectTemplating\Objects\ODContained;

class ODRedirect extends ODContained
{
    public function __construct($id)
    {
        $parent = parent::__construct($id, "oobject/odcontained/odredirect/odredirect.config.phtml");
        $this->properties = $parent->properties;
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
        return $this;
    }

    public function setRoute($route)
    {
        $route = (string) $route;
        $properties = $this->getProperties();
        $properties['route'] = $route;
        $this->setProperties($properties);
        return $this;
    }

    public function setController($controller)
    {
        $controller = (string) $controller;
        $properties = $this->getProperties();
        $properties['controller'] = $controller;
        $this->setProperties($properties);
        return $this;
    }

    public function setAction($action)
    {
        $action = (string) $action;
        $properties = $this->getProperties();
        $properties['action'] = $action;
        $this->setProperties($properties);
        return $this;
    }

    public function addParam($id, $valeur)
    {
        $id = (string) $id;
        $properties = $this->getProperties();
        if (!isset($properties['params'])) $properties['params'] = [];
        $properties['params'][$id] = $valeur;
        $this->setProperties($properties);
        return $this;
    }

}