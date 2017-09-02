<?php

namespace GraphicObjectTemplating\Objects;

use GraphicObjectTemplating\Objects\OSContainer\OSDiv;

class OEObject extends OSDiv
{
    public function __construct($id, $pathConfig, $className)
    {
        parent::__construct($id);
        $properties  = include($pathConfig);
        foreach ($this->properties as $key => $property) {
            if (!array_key_exists($key, $properties)) {$properties[$key] = $property; }
        }

        $properties['className'] = $className;
        $templateName = 'graphic-object-templating/oobject/' . $properties['typeObj'];
        $templateName .= '/' . $properties['object'] . '/' . $properties['template'];
        $properties['template'] = $templateName;

        $this->setProperties($properties);
        return $this;
    }
}