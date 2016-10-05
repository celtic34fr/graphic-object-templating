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
 * setTransition($transition = self::TRANSITION_NONE)
 * getTransition()
 * setTitle($title = "")
 * getTitle()
 * enaScrolling()
 * disScrolling()
 * setOpacity($opacity = self::OPACITY_DEFAULT)
 * getOpacity()
 * setContent($content)
 * getContent()
 */
class OSDialog extends OSContainer
{
    const TRANSITION_NONE    = "none";
    const TRANSITION_FADE    = "fade";
    const TRANSITION_ELASTIC = "elastic";

    const OPACITY_DEFAULT    = 85;

    protected $const_transition;

    public function __construct($id) {
        parent::__construct($id, "oobject/oscontainer/osdialog/osdialog.config.phtml");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function setTransition($transition = self::TRANSITION_NONE)
    {
        $transitions = $this->getTransitionConstants();
        if (!in_array($transition, $transitions)) $transition = self::TRANSITION_NONE;

        $properties = $this->getProperties();
        $properties['transition'] = $transition;
        $this->setProperties($properties);
        return $this;
    }

    public function getTransition()
    {
        $properties         = $this->getProperties();
        return ((!empty($properties['transition'])) ? $properties['transition'] : false) ;
    }

    public function setTitle($title = "")
    {
        $title = (string) $title;
        $properties = $this->getProperties();
        $properties['title'] = $title;
        $this->setProperties($properties);
        return $this;
    }

    public function getTitle()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('title', $properties)) ? $properties['title'] : false ;
    }

    public function enaScrolling()
    {
        $properties = $this->getProperties();
        $properties['scrolling'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function disScrolling()
    {
        $properties = $this->getProperties();
        $properties['scrolling'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function setOpacity($opacity = self::OPACITY_DEFAULT)
    {
        $opacity = (int) $opacity;
        if ($opacity < 0) $opacity = 0;
        if ($opacity > 100) $opacity = 100;

        $properties = $this->getProperties();
        $properties['opacity'] = $opacity / 100;
        $this->setPosition($properties);
        return $this;
    }

    public function getOpacity()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('opacity', $properties)) ? $properties['opacity']*100 : false ;
    }

    public function enaScalePhoto()
    {
        $properties = $this->getProperties();
        $properties['scalePhoto'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function disScalePhoto()
    {
        $properties = $this->getProperties();
        $properties['scalePhoto'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function enaCloseButton()
    {
        $properties = $this->getProperties();
        $properties['closeButton'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function disCloseButton()
    {
        $properties = $this->getProperties();
        $properties['closeButton'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function setWidth($width = false)
    {
        if (empty($width) || intval($width) == 0) { $width = false; }
        else { $width = (int) $width; }

        $properties = $this->getProperties();
        $properties['width'] = $width;
        $this->setProperties($properties);
        return $this;
    }

    public function getWidth()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('width', $properties)) ? $properties['width'] : false ;
    }

    public function setHeight($height = false)
    {
        if (empty($height) || intval($height) == 0) { $height = false; }
        else { $height = (int) $height; }

        $properties = $this->getProperties();
        $properties['height'] = $height;
        $this->setProperties($properties);
        return $this;
    }

    public function getHeight()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('height', $properties)) ? $properties['height'] : false ;
    }

    public function setInitialWidth($initialWidth = false)
    {
        if (empty($initialWidth) || intval($initialWidth) == 0) { $initialWidth = false; }
        else { $initialWidth = (int) $initialWidth; }

        $properties = $this->getProperties();
        $properties['initialWidth'] = $initialWidth;
        $this->setProperties($properties);
        return $this;
    }

    public function getInitialWidth()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('initialWidth', $properties)) ? $properties['initialWidth'] : false ;
    }

    public function setInitialHeight($initialHeight = false)
    {
        if (empty($initialHeight) || intval($initialHeight) == 0) { $initialHeight = false; }
        else { $initialHeight = (int) $initialHeight; }

        $properties = $this->getProperties();
        $properties['initialHeight'] = $initialHeight;
        $this->setProperties($properties);
        return $this;
    }

    public function getInitialHeight()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('initialHeight', $properties)) ? $properties['initialHeight'] : false ;
    }

    public function setMaxWidth($maxWidth = false)
    {
        if (empty($maxWidth) || intval($maxWidth) == 0) { $maxWidth = false; }
        else { $maxWidth = (int) $maxWidth; }

        $properties = $this->getProperties();
        $properties['maxWidth'] = $maxWidth;
        $this->setProperties($properties);
        return $this;
    }

    public function getMaxWidth()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('maxWidth', $properties)) ? $properties['maxWidth'] : false ;
    }

    public function setMaxHeight($maxHeight = false)
    {
        if (empty($maxHeight) || intval($maxHeight) == 0) { $maxHeight = false; }
        else { $maxHeight = (int) $maxHeight; }

        $properties = $this->getProperties();
        $properties['maxHeight'] = $maxHeight;
        $this->setProperties($properties);
        return $this;
    }

    public function getMaxHeight()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('maxHeight', $properties)) ? $properties['maxHeight'] : false ;
    }

    public function enaResize()
    {
        $properties = $this->getProperties();
        $properties['reposition'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function disResize()
    {
        $properties = $this->getProperties();
        $properties['reposition'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function enaFixed()
    {
        $properties = $this->getProperties();
        $properties['fixed'] = true;
        $this->setPosition($properties);
        return $this;
    }

    public function disFixed()
    {
        $properties = $this->getProperties();
        $properties['fixed'] = true;
        $this->setPosition($properties);
        return $this;
    }


    private function getTransitionConstants()
    {
        $retour = [];
        if (empty($this->const_transition)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TRANSITION');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_transition = $retour;
        } else {
            $retour = $this->const_transition;
        }
        return $retour;
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

}