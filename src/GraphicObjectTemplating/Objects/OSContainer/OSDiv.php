<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 11:01
 */

namespace GraphicObjectTemplating\Objects\OSContainer;

use GraphicObjectTemplating\Objects\OSContainer;

class OSDiv extends OSContainer
{
    public function __construct($id) {
        parent::__construct($id, "oobject/oscontainer/osdiv/osdiv.config.phtml");
        $this->setDisplay();
    }
}