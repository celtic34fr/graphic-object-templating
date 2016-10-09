<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 03/10/16
 * Time: 23:30
 */

namespace GraphicObjectTemplating\Objects\OSContainer;

use GraphicObjectTemplating\Objects\ODContained\ODContent;
use GraphicObjectTemplating\Objects\OObject;
use GraphicObjectTemplating\Objects\OSContainer;

/**
 * Class OSDialog
 * @package GraphicObjectTemplating\Objects\OSContainer
 *
 * objet de dialogue sur base jQuery Colorbox
 *
 * enaAccessible()
 * disAccessible()
 * enable()
 * disable()
 * setContent($content)
 * getContent()
 * setEffect($effect = self::EFFECT_NONE)
 * getEffect()
 * setClass($class = "")
 * getClass()
 * enaClickoff()
 * disClickoff()
 * setInner($inner = "modality-inner")
 * getInner()
 * setOuter($outer = "modality-outer")
 * getOuter()
 * enaKeyboard()
 * disKeyboard()
 * enaOpen()
 * disOpen()
 *
 * CmdOpenDialog()
 * CmdCloseDialog()
 * CmdToggleDialog()
 */
class OSDialog extends OSContainer
{
    const EFFECT_NONE            = "";
    const EFFECT_SCALE_UP        = "scale_up";
    const EFFECT_SCALE_DOWN      = "scale_down";
    const EFFECT_SLIDE_LEFT      = "slide_left";
    const EFFECT_SLIDE_RIGHT     = "slide_right";
    const EFFECT_SLIDE_UP        = "slide_up";
    const EFFECT_SLIDE_DOWN      = "slide_down";
    const EFFECT_STICKY_TOP      = "sticky_top";
    const EFFECT_STICKY_BOTTOM   = "sticky_bottom";
    const EFFECT_HORIZONTAL_FLIP = "horizontal_flip";
    const EFFECT_VERTICAL_FLIP   = "vertical_flip";
    const EFFECT_SPIN_UP         = "spin_up";
    const EFFECT_SPIN_DOWN       = "spin_down";
    const EFFECT_FALL_LEFT       = "fall_left";
    const EFFECT_FALL_RIGHT      = "fall_right";
    const EFFECT_SWING_DOWN      = "swing_down";
    const EFFECT_SWING_UP        = "swing_up";
    const EFFECT_SWING_LEFT      = "swing_left";
    const EFFECT_SWING_RIGHT     = "swing_right";
    const EFFECT_FRONT_FLIP      = "front_flip";
    const EFFECT_BACK_FLIP       = "back_flip";

    protected $const_effect;

    public function __construct($id) {
        parent::__construct($id, "oobject/oscontainer/osdialog/osdialog.config.phtml");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function enaAccessible()
    {
        $properties = $this->getProperties();
        $properties['accessible'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disAccessible()
    {
        $properties = $this->getProperties();
        $properties['enabled'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function enable()
    {
        $properties = $this->getProperties();
        $properties['enabled'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disable()
    {
        $properties = $this->getProperties();
        $properties['enabled'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function setContent($content)
    {
        $properties = $this->getProperties();
        if (!($content instanceof OObject)) {
            $contenu = new ODContent($properties['id']."Content");
            $contenu->setContent($content);
            $this->addChild($contenu);
        } else {
            $this->addChild($content);
        }
        return $this;
    }

    public function getContent()
    {
        if ($this->$this->hasChildren()) return $this->getChildren();
        return false;
    }

    public function setEffect($effect = self::EFFECT_NONE)
    {
        $effect = (string) $effect;
        $effects = $this->getEffectsConstants();
        if (!in_array($effect, $effects)) $effect = self::EFFECT_NONE;

        $properties = $this->getProperties();
        $properties['effect'] = $effect;
        $this->setProperties($properties);
        return $this;
    }

    public function getEffect()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['effect'])) ? $properties['effect'] : false) ;
    }

    public function setClass($class = "")
    {
        $class = (string) $class;
        if (!empty($class)) {
            $properties = $this->getProperties();
            $properties['class'] = $class;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getClass()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['class'])) ? $properties['class'] : false) ;
    }

    public function enaClickoff()
    {
        $properties = $this->getProperties();
        $properties['clickoff'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disClickoff()
    {
        $properties = $this->getProperties();
        $properties['clickoff'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function setInner($inner = "modality-inner")
    {
        $inner = (string) $inner;
        $properties = $this->getProperties();
        $properties['inner'] = $inner;
        $this->setProperties($properties);
        return $this;
    }

    public function getInner()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['inner'])) ? $properties['inner'] : false) ;
    }

    public function setOuter($outer = "modality-outer")
    {
        $outer = (string) $outer;
        $properties = $this->getProperties();
        $properties['outer'] = $outer;
        $this->setProperties($properties);
        return $this;
    }

    public function getOuter()
    {
        $properties = $this->getProperties();
        return ((!empty($properties['outer'])) ? $properties['outer'] : false) ;
    }

    public function enaKeyboard()
    {
        $properties = $this->getProperties();
        $properties['keyboard'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disKeyboard()
    {
        $properties = $this->getProperties();
        $properties['keyboard'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function enaOpen()
    {
        $properties = $this->getProperties();
        $properties['open'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disOpen()
    {
        $properties = $this->getProperties();
        $properties['open'] = false;
        $this->setProperties($properties);
        return $this;
    }



    public function CmdOpenDialog()
    {
        $item = [];

        $item['id']   = $this->getId()."Command";
        $item['mode'] = "exec";
        $item['html'] = "var inst = $.modality.instances['".$this->getId()."Content']; inst.open();";

        return array($item);
    }

    public function CmdCloseDialog()
    {
        $item = [];

        $item['id']   = $this->getId."Command";
        $item['mode'] = "exec";
        $item['html'] = "var inst = $.modality.instances['".$this->getId()."Content']; inst.close();";

        return array($item);
    }

    public function CmdToggleDialog()
    {
        $item = [];

        $item['id']   = $this->getId."Command";
        $item['mode'] = "exec";
        $item['html'] = "var inst = $.modality.instances['".$this->getId()."Content']; inst.toggle();";

        return array($item);
    }



    private function getEffectsConstants()
    {
        $retour = [];
        if (empty($this->const_effect)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'EFFECT');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_effect = $retour;
        } else {
            $retour = $this->const_effect;
        }
        return $retour;
    }

}