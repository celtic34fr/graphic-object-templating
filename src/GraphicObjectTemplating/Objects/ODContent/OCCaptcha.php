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

    public function setLabel($label)
    {
        $label = (string) $label;
        $properties          = $this->getProperties();
        $properties['label'] = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('label', $properties)) ? $properties['label'] : false);
    }

    public function setLabelWidthBT($widthBT)
    {
        $properties                 = $this->getProperties();
        $properties['labelWidthBT'] = $widthBT;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabelWidthBT()
    {
        $properties                = $this->getProperties();
        return ((!empty($properties['labelWidthBT'])) ? $properties['labelWidthBT'] : false) ;
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