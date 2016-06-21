<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 15/06/16
 * Time: 23:37
 */

namespace GraphicObjectTemplating\Objects\ODContent;


use GraphicObjectTemplating\Objects\ODContent;
use Zend\Session\Container;

class OCCaptcha extends ODContent
{
    const ALPHABETIC   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const NUMERIC      = '0123456789';
    const ALPHANUMERIC = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

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
        $properties                   = $this->getProperties();
        $properties['label']          = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties                   = $this->getProperties();
        return ((array_key_exists('label', $properties)) ? $properties['label'] : false);
    }

    public function setLabelWidthBT($widthBT)
    {
        $properties                   = $this->getProperties();
        $properties['labelWidthBT']   = $widthBT;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabelWidthBT()
    {
        $properties                   = $this->getProperties();
        return ((!empty($properties['labelWidthBT'])) ? $properties['labelWidthBT'] : false) ;
    }

    public function setLength($length = 6)
    {
        $length = (int) $length;
        $properties                   = $this->getProperties();
        $properties['length']         = $length;
        $this->setProperties($properties);
        return $this;
    }

    public function getLength()
    {
        $properties                   = $this->getProperties();
        return ((!empty($properties['length'])) ? $properties['length'] : false) ;
    }

    public function includeNumbers($include = false)
    {
        $include = (bool) $include;
        $properties                   = $this->getProperties();
        $properties['includeNumbers'] = $include;
        $this->setProperties($properties);
        return $this;
    }

    public function isIncludeNumbers()
    {
        $properties                   = $this->getProperties();
        return ((!empty($properties['includeNumbers'])) ? $properties['includeNumbers'] : false) ;
    }

    public function setChars($chars = self::ALPHANUMERIC)
    {
        $charsType = array(self::ALPHANUMERIC, self::ALPHABETIC, self::NUMERIC);
        if (!in_array($chars, $charsType)) $chars = self::ALPHANUMERIC;
        $properties                   = $this->getProperties();
        $properties['chars']          = $chars;
        $this->setProperties($properties);
        return $this;
    }

    public function getChars()
    {
        $properties                   = $this->getProperties();
        return ((!empty($properties['chars'])) ? $properties['chars'] : false) ;
    }

    public function setColor($color = "000000")
    {
        $color = (string) $color;
        $properties                   = $this->getProperties();
        $properties['color']          = $color;
        $this->setProperties($properties);
        return $this;
    }

    public function getColor()
    {
        $properties                   = $this->getProperties();
        return ((!empty($properties['color'])) ? $properties['color'] : false) ;
    }

    public function setBackgroud($background = "FFA077")
    {
        $background = (string) $background;
        $properties                   = $this->getProperties();
        $properties['background']     = $background;
        $this->setProperties($properties);
        return $this;
    }

    public function getBackground()
    {
        $properties                   = $this->getProperties();
        return ((!empty($properties['background'])) ? $properties['background'] : false) ;
    }

    public function setFont($font)
    {
        $font = (string) $font;
        $properties                   = $this->getProperties();
        $properties['font']           = $font;
        $this->setProperties($properties);
        return $this;
    }

    public function getFont()
    {
        $properties                   = $this->getProperties();
        return ((!empty($properties['font'])) ? $properties['font'] : false) ;
    }

    public function generateCaptcha()
    {
        $session = new Container("captcha".$this->getId());
        $dir = '../fonts/';

        $chars = $this->getChars();
        $length = $this->getLength();
        $font = $this->getFont(); 
        $captcha_code = '';

        for ($i = 0; $i < $length; $i++) {
            $captcha_code .= substr($chars, rand(strlen($chars) - 1), 1);
        }

        $session->code = $captcha_code;

        $target_layer = imagecreatetruecolor(70, 30);
        $captcha_background = imagecolorallocate($target_layer, 255, 160, 119);
        imagefill($target_layer, 0, 0, $captcha_background);
        $captcha_text_color = imagecolorallocate($target_layer, 0, 0, 0);
        imagettftext ($target_layer, 30, 0, 10, 40, $captcha_text_color, $dir.$font, $captcha_code);

        header("Content-type: image/jpeg");
        return imagejpeg($target_layer);
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