<?php

namespace GraphicObjectTemplating\OObjects\OSContainer;

use GraphicObjectTemplating\OObjects\OSContainer;

class OSDiv extends OSContainer
{
    public function __construct($id) {
        parent::__construct($id, "oobjects/oscontainer/osdiv/osdiv.config.php");
        $this->id = $id;
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
        $this->enable();
        return $this;
    }
}