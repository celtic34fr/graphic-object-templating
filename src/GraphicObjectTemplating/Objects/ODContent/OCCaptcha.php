<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 15/06/16
 * Time: 23:37
 */

namespace GraphicObjectTemplating\Objects\ODContent;


use GraphicObjectTemplating\Objects\ODContent;

class OCCaptcha extends ODContent
{
    public function __construct($id) {
        parent::__construct($id, "oobject/odcontent/occaptcha/occaptcha.config.phtml");
        $this->setDisplay();
    }

    public function validSaisie()
    {
        
    }
}