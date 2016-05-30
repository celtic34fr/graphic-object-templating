<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 10:49
 */
namespace GraphicObjectTemplating\Objects\ODContent;

use GraphicObjectTemplating\Objects\ODContent;

/**
 * Class OCInput
 * @package GraphicObjectTemplating\Objects\ODContent
 *
 * setType
 * getType
 * setSize
 * getSize
 * setMaxlength
 * getMaxlength
 * setLabel
 * getLabel
 * enable
 * disable
 * setPlaceholder
 * getPlaceholder
 * setLabelWidthBT
 * getLabelWidthBT
 * setErrMessage
 * getErrMessage
 * evtChange
 * disChange
 * evtKeypress
 * disKeypress
 */
class OCInput extends ODContent
{
    const TYPE =array(
        'TEXT'     => 'text',
        'PASSWORD' => 'password',
        'HIDDEN'   => 'hidden');

    const STATE = array(
        'ENABLE'  => true,
        'DISABLE' => false);

    public function __construct($id) {
        parent::__construct($id, "oobject/odcontent/ocinput/ocinput.config.phtml");
        $this->setDisplay();
    }

    public function setType($type = self::TYPE['TEXT']) {
        $types = $this->getTypesConstants();
        $type = (string) $type;
        if (!in_array($type, $types)) $type = self::TYPE['TEXT'];

        $properties         = $this->getProperties();
        $properties['type'] = $type;
        $this->setProperties($properties);
        return $this;
    }

    public function getType() {
        $properties            = $this->getProperties();
        return ((array_key_exists('type', $properties)) ? $properties['type'] : false);
    }

    public function setSize($size = 0)
    {
        $size = (int) $size;
        if ($size < 0) $size = 0;

        $properties         = $this->getProperties();
        $properties['size'] = $size;
        $this->setProperties($properties);
        return $this;
    }

    public function getSize()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('size', $properties)) ? $properties['size'] : false);
    }

    public function setMaxlength($maxlength = 0)
    {
        $maxlength = (int) $maxlength;
        if ($maxlength < 0) $maxlength = 0;

        $properties              = $this->getProperties();
        $properties['maxlength'] = $maxlength;
        $this->setProperties($properties);
        return $this;
    }

    public function getMaxlength()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('maxlength', $properties)) ? $properties['maxlength'] : false);
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

    public function enable()
    {
        $properties          = $this->getProperties();
        $properties['state'] = self::STATE['ENABLE'];
        $this->setProperties($properties);
        return $this;
    }

    public function disable()
    {
        $properties          = $this->getProperties();
        $properties['state'] = self::STATE['DISABLE'];
        $this->setProperties($properties);
        return $this;
    }

    public function setPlaceholder($placeholder)
    {
        $placeholder = (string) $placeholder;
        $properties                = $this->getProperties();
        $properties['placeholder'] = $placeholder;
        $this->setProperties($properties);
        return $this;
    }

    public function getPlaceholder()
    {
        $properties                = $this->getProperties();
        return ((!empty($properties['placeholder'])) ? $properties['placeholder'] : false) ;
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

    public function setErrMessage($errMessage)
    {
        $errMessage = (string) $errMessage;
        $properties               = $this->getProperties();
        $properties['errMessage'] = $errMessage;
        $this->setProperties($properties);
        return $this;
    }

    public function getErrMessage()
    {
        $properties                = $this->getProperties();
        return ((!empty($properties['errMessage'])) ? $properties['errMessage'] : false) ;
    }

    public function evtChange($callback)
    {
        $properties = $this->getProperties();
        if(!isset($properties['event'])) $properties['event']= [];
        $properties['event']['change'] = $callback;
        $this->setProperties($properties);
        return $this;
    }

    public function disChange()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']['change'])) unset($properties['event']['change']);
        $this->setProperties($properties);
        return $this;
    }

    public function evtKeyup($callback)
    {
        $properties = $this->getProperties();
        if(!isset($properties['event'])) $properties['event']= [];
        $properties['event']["keyup"] = $callback;
        $this->setProperties($properties);
        return $this;
    }

    public function disKeyup()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']["keyup"])) unset($properties['event']["keyup"]);
        $this->setProperties($properties);
        return $this;
    }

    public function setIcon($icon)
    {
        $icon               = (string) $icon;
        $properties         = $this->getProperties();
        $properties['icon'] = $icon;
        $this->setProperties($properties);
        return $this;
    }

    public function getIcon()
    {
        $properties         = $this->getProperties();
        return ((!empty($properties['icon'])) ? $properties['icon'] : false) ;
    }

    /*
     * méthode interne à la classe OObject
     */

    private function getTypesConstants()
    {
        return self::TYPE;
    }

}