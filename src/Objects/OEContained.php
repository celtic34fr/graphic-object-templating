<?php
/**
 * Created by PhpStorm.
 * User: candidat
 * Date: 04/09/17
 * Time: 11:35
 */

namespace GraphicObjectTemplating\Objects;

use Exception;

class OEContained extends OEObject
{
    private $_tExtends = ['GraphicObjectTemplating\Objects\ODContained'];
    private $_tExtendInstances = [];

    public function __construct($id, $pathConfig, $className)
    {
        parent::__construct($id, $pathConfig, $className);
        $properties = $this->getProperties();
        foreach ($this->_tExtends as $tExtend) {
            /** @var OObject $tmpObj */
            $tmpObj = new $tExtend($id);
            $this->_tExtendInstances[] = $tmpObj;
            $tmpProperties = $tmpObj->getProperties();
            foreach ($tmpProperties as $key => $tmpProperty) {
                if (!array_key_exists($key, $properties))
                        {$properties[$key] = $tmpProperties; }

            }
        }
        $this->setProperties($properties);
        return $this;
    }

    public function __call($funcName, $tArgs)
    {
        foreach($this->_tExtendInstances as &$object) {
            if(method_exists($object, $funcName)) 
             { return call_user_func_array(array($object, $funcName), $tArgs); }
        }
        throw new Exception("The $funcName method doesn't exist");
    }

    public function __get($varName) {
        foreach($this->_tExtendInstances as &$object) {
            $tDefinedVars   = get_defined_vars($object);
            if(property_exists($tDefinedVars, $varName))
                    { return $object->{$varName}; }
        }
        throw new Exception("The $varName attribute doesn't exist");
    }
}