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

    public function isRealPerson($capcha)
    {
        return ((rpHash($capcha) == $capcha) ? true : false );
    }
    
    private function rpHash($value)
    {
        $hash = 5381;
        $value = strtoupper($value);
        for ($i = 0; $i < strlen($value); $i++) {
            $hash = (($hash << 5) + $hash) + ord(substr($value, $i));
        }
        return $hash;
    }
    
}