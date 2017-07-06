<?php
/**
 * Created by PhpStorm.
 * User: candidat
 * Date: 06/07/17
 * Time: 16:55
 */

namespace GraphicObjectTemplating\Objects\ODContained;


use GraphicObjectTemplating\Objects\ODContained;
use graphicObjectTEmplating\Objects\OObject;

class ODMenu extends ODContained
{
    public function __construct($id)
    {
        $obj = OObject::buildObject($id);
        if ($obj === FALSE) {
            parent::__construct($id, "oobject/odcontained/odmenu/odmenu.config.phtml");
        } else {
            $this->setProperties($obj->getProperties());
        }
        $this->id = $id;
        $this->setDisplay();
    }

}