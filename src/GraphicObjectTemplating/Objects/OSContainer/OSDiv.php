<?php

namespace GraphicObjectTemplating\Objects\OSContainer;

use GraphicObjectTemplating\Objects\OSContainer;

class OSDiv extends OSContainer
{
    public function __construct($id) {
        $obj = OObject::buildObject($id);
        if ($obj === FALSE) {
            parent::__construct($id, "oobject/oscontainer/osdiv/osdiv.config.php");
        } else {
            $this->setProperties($obj->getProperties());
        }
        $this->id = $id;
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }
}