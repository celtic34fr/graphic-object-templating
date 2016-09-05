<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

class ODToggle extends ODContained
{
    const TOGGLEPOS_LEFT = "left";
    const TOGGLEPOS_RIGHT = "right";

    const TOGGLEDATA_ON   = "On";
    const TOGGLEDATA_OFF  = "Off";

    const TOGGLESIZE_LARGE  = "large";
    const TOGGLESIZE_NORMAL = "normal";
    const TOGGLESIZE_SMALL  = "small";
    const TOGGLESIZE_MINI   = "mini";

    const TOGGLESTYLE_PRIMARY = "primary";
    const TOGGLESTYLE_SUCCESS = "success";
    const TOGGLESTYLE_INFO    = "info";
    const TOGGLESTYLE_WARNING = "warning";
    const TOGGLESTYLE_DANGER  = "danger";
    const TOGGLESTYLE_DEFAULT = "default";

    const TOGGLECUSTOM_IOS    = "ios";
    const TOGGLECUSTOM_ANDROID = "android";

    protected $const_toggleSize;
    protected $const_toggleStyle;
    protected $const_toggleCustom;

    public function __construct($id) {
        $parent = parent::__construct($id, "oobject/odcontained/odtoggle/odtoggle.config.phtml");
        $this->properties = $parent->properties;
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function setLabel($label)
    {
        $label               = (string) $label;
        $properties          = $this->getProperties();
        $properties['label'] = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['label'])) ? $properties['label'] : false) ;
    }

    public function setPosition($position = self::TOGGLEPOS_RIGHT)
    {
        $properties = $this->getProperties();
        $properties['position'] = $position;
        $this->setProperties($properties);
        return $this;
    }

    public function getPosition()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['position'])) ? $properties['posirion'] : false) ;
    }

    public function setDataOn($dataOn = self::TOGGLEDATA_ON)
    {
        $properties = $this->getProperties();
        $properties['dataOn'] = $dataOn;
        $this->setProperties($properties);
        return $this;
    }

    public function getDataOn()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['dataOn'])) ? $properties['dataOn'] : false) ;
    }

    public function setDataOff($dataOff = self::TOGGLEDATA_OFF)
    {
        $properties = $this->getProperties();
        $properties['dataOff'] = $dataOff;
        $this->setProperties($properties);
        return $this;
    }

    public function getDataOff()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['dataOff'])) ? $properties['dataOff'] : false) ;
    }

    public function setSize($size = self::TOGGLESIZE_MINI)
    {
        $sizes = $this->getToggleSizeConstants();
        if (!in_array($size, $sizes)) $size = self::TOGGLESIZE_MINI;

        $properties = $this->getProperties();
        $properties['size'] = $size;
        $this->setProperties($properties);
        return $this;
    }

    public function getSize() {
        $properties         = $this->getProperties();
        return ((!empty($properties['size'])) ? $properties['size'] : false) ;
    }

    public function setOnStyle($style = self::TOGGLESTYLE_PRIMARY)
    {
        $styles = $this->getToggleStyleConstants();
        if (!in_array($style, $styles)) $style = self::TOGGLESTYLE_PRIMARY;

        $properties = $this->getProperties();
        $properties['onStyle'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getOnStyle() {
        $properties         = $this->getProperties();
        return ((!empty($properties['onStyle'])) ? $properties['onStyle'] : false) ;
    }

    public function setOffStyle($style = self::TOGGLESTYLE_DEFAULT)
    {
        $styles = $this->getToggleStyleConstants();
        if (!in_array($style, $styles)) $style = self::TOGGLESTYLE_DEFAULT;

        $properties = $this->getProperties();
        $properties['offStyle'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getOffStyle() {
        $properties         = $this->getProperties();
        return ((!empty($properties['offStyle'])) ? $properties['offStyle'] : false) ;
    }

    public function setWidth($width)
    {
        $width = (int) $width;
        $properties = $this->getProperties();
        $properties['width'] = $width;
        $this->setProperties($properties);
        return $this;
    }

    public function getWidth() {
        $properties         = $this->getProperties();
        return ((!empty($properties['width'])) ? $properties['width'] : false) ;
    }

    public function setHeight($height)
    {
        $height = (int) $height;
        $properties = $this->getProperties();
        $properties['height'] = $height;
        $this->setProperties($properties);
        return $this;
    }

    public function getHeight() {
        $properties         = $this->getProperties();
        return ((!empty($properties['height'])) ? $properties['height'] : false) ;
    }

    public function setCustomStyle($style = "")
    {
        $styles = $this->getToggleCustomConstants();
        if (!in_array($style, $styles)) $style = "";

        $properties = $this->getProperties();
        $properties['custom'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getCustomStyle() {
        $properties         = $this->getProperties();
        return ((!empty($properties['custom'])) ? $properties['custom'] : false) ;
    }

    public function enaToggle()
    {
        $properties = $this->getProperties();
        $properties['toggle'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function disToggle()
    {
        $properties = $this->getProperties();
        $properties['toggle'] = true;
        $this->setPosition($properties);
        return $this;
    }



    /*
     * méthode interne à la classe OObject
     */

    private function getToggleSizeConstants()
    {
        $retour = [];
        if (empty($this->const_toggleSize)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TOGGLESIZE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_toggleSize = $retour;
        } else {
            $retour = $this->const_toggleSize;
        }
        return $retour;
    }

    private function getToggleStyleConstants()
    {
        $retour = [];
        if (empty($this->const_toggleStyle)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TOGGLESTYLE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_toggleStyle = $retour;
        } else {
            $retour = $this->const_toggleStyle;
        }
        return $retour;
    }

    private function getToggleCustomConstants()
    {
        $retour = [];
        if (empty($this->const_toggleCustom)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TOGGLECUSTOM');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_toggleCustom = $retour;
        } else {
            $retour = $this->const_toggleCustom;
        }
        return $retour;
    }

}