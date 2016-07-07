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
        $lxs = 0; $ixs = 0;
        $lsm = 0; $ism = 0;
        $lmd = 0; $imd = 0;
        $llg = 0; $ilg = 0;

        switch (true) {
            case (is_numeric($widthBT)):
                $lxs = $widthBT; $ixs = 12 - $widthBT;
                $lsm = $widthBT; $ism = 12 - $widthBT;
                $lmd = $widthBT; $imd = 12 - $widthBT;
                $llg = $widthBT; $ilg = 12 - $widthBT;
                break;
            default:
                /** widthBT chaîne de caractères */
                $widthBT = explode(":", $widthBT);
                foreach ($widthBT as $item) {
                    $key = strtoupper(substr($item, 0, 2));
                    switch ($key) {
                        case "WX" : $lxs = intval(substr($item,2)); break;
                        case "WS" : $lsm = intval(substr($item,2)); break;
                        case "WM" : $lmd = intval(substr($item,2)); break;
                        case "WL" : $llg = intval(substr($item,2)); break;
                        default:
                            if (substr($key,0,1) == "W") {
                                $wxs = intval(substr($item,1));
                                $wsm = intval(substr($item,1));
                                $wmd = intval(substr($item,1));
                                $wlg = intval(substr($item,1));
                            }
                    }
                }
                $ixs = 12 - $lxs;
                $ism = 12 - $lsm;
                $imd = 12 - $lmd;
                $ilg = 12 - $llg;
                break;
        }
        $properties = $this->getProperties();
        $properties['labelWidthBT']['lxs'] = $lxs;
        $properties['labelWidthBT']['lsm'] = $lsm;
        $properties['labelWidthBT']['lmd'] = $lmd;
        $properties['labelWidthBT']['llg'] = $llg;
        $properties['labelWidthBT']['ixs'] = $ixs;
        $properties['labelWidthBT']['ism'] = $ism;
        $properties['labelWidthBT']['imd'] = $imd;
        $properties['labelWidthBT']['ilg'] = $ilg;
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