<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GraphicObjectTemplating\Objects;

/**
 * Description of OEContainer
 *
 * @author candidat
 */
class OEContainer extends OEObject
{
    private $_tExtends = ['GraphicObjectTemplating\Objects\OSContainer'];
    private $_tExtendIntances = [];
    
    public function __construct($id, $pathConfig, $className) {
        parent::__construct($id, $pathConfig, $className);
        foreach ($this->_tExtends as $tExtend) {
            $this->_tExtendIntances[] = new $tExtend($id);
        }
    }

    public function __call($funcName, $tArgs)
    {
        foreach($this->_tExtendInstances as &$object) {
            if(method_exists($object, $funcName)) 
            { return call_user_func_array(array($object, $funcName), $tArgs); }
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
