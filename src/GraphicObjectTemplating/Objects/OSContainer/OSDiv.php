<?php

namespace GraphicObjectTemplating\Objects\OSContainer;

use GraphicObjectTemplating\Objects\OSContainer;

class OSDiv extends OSContainer
{
    public function __construct($id) {
        parent::__construct($id, "oobject/oscontainer/osdiv/osdiv.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }
}