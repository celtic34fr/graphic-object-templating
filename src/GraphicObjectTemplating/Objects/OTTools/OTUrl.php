<?php

namespace GraphicObjectTemplating\Objects\OTTools;

use GraphicObjectTemplating\Objects\OTTools;

class OTUrl extends OTTools
{
    public function __construct($id)
    {
        parent::__construct($id, "oobject/ottools/oturl/oturl.config.php");
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
        if (empty($properties['controller'])) $properties['controller'] = "Index";
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