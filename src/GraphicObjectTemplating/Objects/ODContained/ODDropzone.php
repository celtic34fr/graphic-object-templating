<?php
/**
 * Created by PhpStorm.
 * User: candidat
 * Date: 26/07/17
 * Time: 13:58
 */

namespace GraphicObjectTemplating\src\GraphicObjectTemplating\Objects\ODContained;


use GraphicObjectTemplating\Objects\ODContained;

class ODDropzone extends ODContained
{
    public function __construct($id) {
        parent::__construct($id, "oobject/odcontained/oddropzone/oddropzone.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
        return $this;
    }
}