<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODDropzone
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * addFilter($name, $filter)
 * rmFilter($name)
 * setFilter($name, $filter)
 * getFilter($name)
 * getFilters()
 * enaMultiple()
 * disMultiple()
 */
class ODDropzone extends ODContained
{
    public function __construct($id) {
        parent::__construct($id, "oobject/odcontained/oddropzone/oddropzone.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
        return $this;
    }

    public function addFilter($name, $filter)
    {
        $name   = (string) $name;
        $filter = (string) $filter;
        $properties = $this->getProperties();
        $filters    = (isset($properties['filters']) && !empty($properties['filters'])) ? $properties['filters'] : array();

        if (!array_key_exists($name, $filters)) {
            $filters[$name] = $filter;

            $properties['filters'] = $filters;
            $this->setProperties( $properties );
            return true;
        } else {
            return false;
        }
    }

    public function rmFilter($name)
    {
        $name       = (string) $name;
        $properties = $this->getProperties();
        $filters    = (isset($properties['filters']) && !empty($properties['filters'])) ? $properties['filters'] : array();

        if (!array_key_exists($name, $filters)) {
            unset($filters[$name]);

            $properties['filters'] = $filters;
            $this->setProperties( $properties );
            return true;
        } else {
            return false;
        }
    }

    public function setFilter($name, $filter)
    {
        $name   = (string) $name;
        $filter = (string) $filter;
        $properties = $this->getProperties();
        $filters    = (isset($properties['filters']) && !empty($properties['filters'])) ? $properties['filters'] : array();

        $filters[$name] = $filter;

        $properties['filters'] = $filters;
        $this->setProperties( $properties );
        return true;
    }

    public function setFilters(array $filters)
    {
        $properties = $this->getProperties();
        $properties['filters'] = $filters;
        $this->setProperties($properties);
        return $this;
    }

    public function getFilter($name)
    {
        $name   = (string) $name;
        $properties = $this->getProperties();

        if (array_key_exists($name, $properties['filters'])) {
            return $properties['filters'][$name];
        }
        return false;
    }

    public function getFilters()
    {
        $properties = $this->getProperties();
        return (isset($properties['filters']) && !empty($properties['filters'])) ? $properties['filters'] : array();
    }

    public function enaMultiple()
    {
        $properties = $this->getProperties();
        $properties['multiple'] = true;
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
}