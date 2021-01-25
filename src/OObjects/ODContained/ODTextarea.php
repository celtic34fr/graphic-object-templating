<?php

namespace GraphicObjectTemplating\OObjects\ODContained;

use GraphicObjectTemplating\OObjects\ODContained;

/**
 * Class ODTextarea
 * @package ZF3_GOT\OObjects\ODContained
 *
 * __construct(string $id)
 * __set($key, $val)
 * enaDispBySide()  : disposition label à coté zone de saisie Textarea
 * enaDispUnder()   : disposition label, et dessous zone de saisie Textarea
 *                  ATTENTION : un setLabelWidthBT après ces 2 dernières commandes annule l'effet attendu pour exécuter
 *                  la commande demandée (setLabelWidthBT)
 * enaResize()      : autorise à redimensionner le textarea en X et Y
 * disResize()      : interdit à redimensionner le textarea en X et Y
 * enaVertiResize   : autorise à redimensionner le textarea en Y
 * enaHorizResize   : autorise à redimensionner le textarea en X
 */
class ODTextarea extends ODContained
{
    const TEXTAREA_RESIZEBOTH   = 'both';
    const TEXTAREA_RESIZEHORIZ  = 'horizontal';
    const TEXTAREA_RESIZEVERTI  = 'vertical';
    const TEXTAREA_RESIZENONE   = 'none';

    private $const_resize;

    protected static $odtextarea_attributes =  ["cols", "rows", "maxLength", "placeholder", "text", 'event',
        'textareaWidthBT', 'labelWidthBT', 'resize', 'wysiwyg', 'plugins', 'toolbar', 'imgListUrl', 'lnkListUrl'];

    /**
     * ODTable constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__ . '/../../../params/oobjects/odcontained/odtextarea/odtextarea.config.php';
        $properties = require $path;
        parent::__construct($id, $properties);

        $properties = $this->object_contructor($id, $properties);
        if ((int)$properties['widthBT'] === 0) {
            $properties['widthBT'] = $this->validate_widthBT(12);
        }
        $this->properties = $properties;
    }

    /**
     * @param string $key
     * @param $val
     * @return mixed|void|null
     */
    public function __set(string $key, $val)
    {
        switch ($key) {
            case 'cols':
            case 'rows':
            case 'maxLength':
                $val = (int) $val;
                $val = ($val === 0) ? "" : $val;
                break;
            case 'placeholder':
            case 'text':
            case 'label':
                $val = (string) $val;
                break;
            case 'labelWidthBT':
            case 'textareaWidthBT':
                if (is_string($val) && strtolower($val[0]) === 'w') {
                    $val = (int)substr($val, 1);
                }
                if (is_numeric($val) and ($val > 12)) {
                    $val = 12;
                }

                $autreWidthBT = ($key === 'labelWidthBT') ? 'labelWidthBT' : 'textareaWidthBT';
                $autreWidthVal = 12 - (int)$val;
                $val = $this->validate_widthBT('W' . $val);
                $properties[$autreWidthBT] = $this->validate_widthBT('W' . $autreWidthVal);
                break;
            case 'resize':
                $val = $this->validate_By_Constants($val, "TEXTAREA_RESIZE", self::TEXTAREA_RESIZEBOTH);
                break;
            default:
                return parent::__set($key, $val);
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @return $this
     */
    public function enaDispBySide()
    {
        $this->labelWidthBT = '';
        $this->textareaWidthBT = '';

        return $this;
    }

    /**
     * @return $this
     */
    public function enaDispUnder()
    {
        $this->labelWidthBT = $this->validate_widthBT(12);
        $this->textareaWidthBT = $this->validate_widthBT(12);

        return $this;
    }

    /**
     * @return $this
     */
    public function enaResize()
    {
        $this->resize = self::TEXTAREA_RESIZEBOTH;
        return $this;
    }

    /**
     * @return $this
     */
    public function disResize()
    {
        $this->resize = self::TEXTAREA_RESIZENONE;
        return $this;
    }

    /**
     * @return $this
     */
    public function enaVertiResize()
    {
        $this->resize = self::TEXTAREA_RESIZEVERTI;
        return $this;
    }

    /**
     * @return $this
     */
    public function enaHorizResize()
    {
        $this->resize = self::TEXTAREA_RESIZEHORIZ;
        return $this;
    }






    /** procédure mis en commentaire pour vérification, voire suppression */


    /**
     * @param $class
     * @param $method
     * @param bool $stopEvent
     * @return $this
     */
//    public function evtChange($class, $method, $stopEvent = true)
//    {
//        $properties = $this->getProperties();
//        if(!isset($properties['event'])) $properties['event']= [];
//        $properties['event']['change'] = [];
//        $properties['event']['change']['class'] = $class;
//        $properties['event']['change']['method'] = $method;
//        $properties['event']['change']['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';
//        $this->setProperties($properties);
//        return $this;
//    }

    /**
     * @return $this
     */
//    public function disChange()
//    {
//        $properties             = $this->getProperties();
//        if (isset($properties['event']['change'])) unset($properties['event']['change']);
//        $this->setProperties($properties);
//        return $this;
//    }

    /**
     * @param $class
     * @param $method
     * @param bool $stopEvent
     * @return $this
     */
//    public function evtKeyup($class, $method, $stopEvent = true)
//    {
//        $properties = $this->getProperties();
//        if(!isset($properties['event'])) $properties['event']= [];
//        $properties['event']['keyup'] = [];
//        $properties['event']['keyup']['class'] = $class;
//        $properties['event']['keyup']['method'] = $method;
//        $properties['event']['keyup']['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';
//        $this->setProperties($properties);
//        return $this;
//    }

    /**
     * @return $this
     */
//    public function disKeyup()
//    {
//        $properties             = $this->getProperties();
//        if (isset($properties['event']["keyup"])) unset($properties['event']["keyup"]);
//        $this->setProperties($properties);
//        return $this;
//    }
}