<?php
/**
 * Created by PhpStorm.
 * User: candidat
 * Date: 08/09/17
 * Time: 09:24
 */

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

class ODImage extends ODContained
{
    public function __construct($id) {
        $parent = parent::__construct($id, "oobject/odcontained/odimage/odimage.config.php");
        $this->properties = $parent->properties;
        $this->setDisplay();
    }

}