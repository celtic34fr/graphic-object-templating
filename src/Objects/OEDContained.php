<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 07/09/17
 * Time: 22:58

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace GraphicObjectTemplating\Objects;


use Exception;

/**
 * Description of OESContainer
 *
 * @author candidat
 */
class OEDContained extends OEObject
{
    private $_tExtends        = 'GraphicObjectTemplating\Objects\ODContained';
    private $_tExtendIntances = "";

    public function __construct($id, $pathConfig, $className) {
        parent::__construct($id, $pathConfig, $className);
        $this->_tExtendIntances = new $this->_tExtends($id);
        return $this;
    }

    public function __call($funcName, $tArgs)
    {
        if(method_exists($this->_tExtendIntances, $funcName))
            { return call_user_func_array(array($this->_tExtendIntances, $funcName), $tArgs); }
        throw new Exception("The $funcName method doesn't exist");
    }

    public function setTExtendInstances(OObject $object)
    {
        $this->_tExtendIntances = $object;
        return $this;
    }
}
