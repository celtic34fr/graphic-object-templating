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
        $parent = parent::__construct($id, "oobject/odcontained/odmenu/odmenu.config.php");
        $this->properties = $parent->properties;
        $this->id = $id;
        $this->setDisplay();
        return $this;
    }

}