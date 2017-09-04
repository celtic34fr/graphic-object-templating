<?php
/**
 * Created by PhpStorm.
 * User: candidat
 * Date: 04/09/17
 * Time: 11:26
 */

namespace GraphicObjectTemplating\Objects;

use Exception;

class OEContainer extends OEObject
{
    private $_tExtends = ['GraphicObjectTemplating\Objects\OSContainer'];
    private $_tExtendInstances = [];

    public function __construct($id, $pathConfig, $className)
    {
        parent::__construct($id, $pathConfig, $className);
        $properties = $this->getProperties();
        foreach ($this->_tExtends as $tExtend) {
            /** @var OObject $tmpObj */
            $tmpObj = new $tExtend($id);
            $tmpProperties = $tmpObj->getProperties();
            $this->_tExtendInstances[] = $tmpObj;
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
            if(method_exists($object, $funcName)) return call_user_func_array(array($object, $funcName), $tArgs);
        }
        throw new Exception("The $funcName method doesn't exist");
    }

    public function __get($nameChild) {
        $properties = $this->getProperties();
        if (!empty($properties['children'])) {
            foreach ($properties['children'] as $idChild => $child) {
                $obj = OObject::buildObject($idChild);
                $name = $obj->getName();
                if ($name == $nameChild) return $obj;
            }
        }
        return false;
    }
}