<?php
/**
 * Created by PhpStorm.
 * User: candidat
 * Date: 04/09/17
 * Time: 11:35
 */

namespace GraphicObjectTemplating\Objects;


class OEContained extends OEObject
{
    private $_tExtends = ['GraphicObjectTemplating\Objects\ODContained'];
    private $_tExtendInstances = [];

    public function __construct($id, $pathConfig, $className)
    {
        parent::__construct($id, $pathConfig, $className);
        $properties = $this->getProperties();
        foreach ($this->_tExtends as $tExtend) {
            $tmpObj = new $tExtend($id);
            $tmpProperties = $tmpObj->getProperties();
            foreach ($tmpProperties as $key => $tmpProperty) {
                if (!array_key_exists($key, $properties))
                {$properties[$key] = $tmpProperties; }

            }
        }
        $this->setProperties($properties);
        return $this;
    }
}