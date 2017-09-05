<?php
/**
 * Created by PhpStorm.
 * User: candidat
 * Date: 05/09/17
 * Time: 11:47
 */

namespace GraphicObjectTemplating\Objects;


class OEObject extends OObject
{
    public function __construct($id, $pathConfig, $className)
    {
        // construction juste OOject sans autre chaose
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
