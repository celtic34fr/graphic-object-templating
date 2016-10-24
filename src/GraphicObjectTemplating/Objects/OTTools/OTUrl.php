<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 24/10/16
 * Time: 00:53
 */

namespace GraphicObjectTemplating\Objects\OTTools;

use GraphicObjectTemplating\Objects\OObject;

class OTUrl extends OObject
{
    public function __construct($id)
    {
        parent::__construct($id, "oobject/ottools/oturl/oturl.config.phtml");
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