<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 26/02/16
 * Time: 11:48
 */

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODNotification
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * fait avec le plugin jQuery Lobibox
 * (http://lobianijs.com/site/lobibox)
 * 
 * setAction
 * getAction
 * statusSound
 * enaSound
 * disSound
 * toggleSound
 * setPosition
 * getPosition
 * setTitle
 * getTitle
 * setBody
 * getBody
 * setType
 * getType
 * setDelay
 * getDelay
 * setSize
 * getSize
 */
class ODNotification extends ODContained
{

    const ACTION_INIT   = 'init';
    const ACTION_SEND   = 'send';

    const SOUND_ENABLE  = 'enable';
    const SOUND_DISABLE = 'disable';

    const POSITION_BR   = 'bottom right';
    const POSITION_BL   = 'bottom left';
    const POSITION_TR   = 'top right';
    const POSITION_TL   = 'top left';

    const TYPE_INFO     = 'info';
    const TYPE_SUCCESS  = 'success';
    const TYPE_WARNING  = 'warning';
    const TYPE_ERROR    = 'error';
    
    const SIZE_MINI     = 'mini';
    const SIZE_NORMAL   = 'normal';
    const SIZE_LARGE    = 'large';

    protected $const_mesAction;
    protected $const_mesSound;
    protected $const_mesPosition;
    protected $const_mesType;
    protected $const_mesSize;

    public function __construct($id) {
        parent::__construct($id, "oobject/odcontained/odnotification/odnotification.config.php");
        $this->setDisplay();
    }

    public function setAction($action = self::ACTION_INIT)
    {
        $actions = $this->getActionsContants();
        if (!in_array($action, $actions)) $action = self::ACTION_INIT;

        $properties           = $this->getProperties();
        $properties['action'] = $action;
        $this->setProperties($properties);
        return $this;
    }

    public function getAction()
    {
        $properties                              = $this->getProperties();
        return (array_key_exists('action', $properties)) ? $properties['action'] : false ;
    }

    public function statusSound()
    {
        $properties           = $this->getProperties();
        $sound   = $properties['sound'];
        return (($sound === true) ? 'enable' : 'disable');
    }

    public function enaSound()
    {
        $properties          = $this->getProperties();
        $properties['sound'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disSound()
    {
        $properties          = $this->getProperties();
        $properties['sound'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function toggleSound()
    {
        $properties          = $this->getProperties();
        $sound               = $properties['sound'];
        $properties['sound'] = ($sound === true) ? false : true;
        $this->setProperties($properties);
        return $this;
    }

    public function setPosition($position = self::POSITION_BR)
    {
        $positions = $this->getPositionsContants();
        if(!in_array($position, $positions)) $position = self::POSITION_BR;

        $properties             = $this->getProperties();
        $properties['position'] = $position;
        $this->setProperties($properties);
        return $this;
    }

    public function getPosition()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('position', $properties)) ? $properties['position'] : false ;
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

    public function setBody($body = "")
    {
        $body = (string) $body;
        $properties = $this->getProperties();
        $properties['body'] = $body;
        $this->setProperties($properties);
        return $this;
    }

    public function getBody()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('body', $properties)) ? $properties['body'] : false ;
    }

    public function setType($type = self::TYPE_INFO)
    {
        $types = $this->getTypesContants();
        if(!in_array($type, $types)) $type = self::TYPE_INFO;

        $properties             = $this->getProperties();
        $properties['type']     = $type;
        $this->setProperties($properties);
        return $this;
    }

    public function getType()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('type', $properties)) ? $properties['type'] : false ;
    }

    public function setDelay($delay = 3000)
    {
        $delay = (int) $delay;
        $properties = $this->getProperties();
        $properties['delay'] = $delay;
        $this->setProperties($properties);
        return $this;
    }

    public function getDelay()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('delay', $properties)) ? $properties['delay'] : false ;
    }

    public function setSize($size = self::SIZE_NORMAL)
    {
        $sizes = $this->getSizesContants();
        if(!in_array($size, $sizes)) $size = self::SIZE_NORMAL;

        $properties             = $this->getProperties();
        $properties['size']     = $size;
        $this->setProperties($properties);
        return $this;
    }

    public function getSize()
    {
        $properties             = $this->getProperties();
        return ((!empty($properties['title'])) ? $properties['title'] : false) ;
    }

    public function enaDistinctMessage()
    {
        $properties = $this->getProperties();
        $properties['showAfterPrevious'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disDistinctMessage()
    {
        $properties = $this->getProperties();
        $properties['showAfterPrevious'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function setDelayMessage($delayMessage = 2000)
    {
        $delayMessage = (int) $delayMessage;
        if ($delayMessage == 0) $delayMessage = 2000;

        $properties = $this->getProperties();
        $properties['delayMessage'] = $delayMessage;
        $this->setProperties($properties);
        return $this;
    }

    public function getDelayMessage()
    {
        $properties             = $this->getProperties();
        return (array_key_exists('delayMessage', $properties)) ? $properties['delayMessage'] : false ;
    }

    /*
     * méthode interne à la classe OObject
     */

    private function getActionsContants()
    {
        $retour = [];
        if (empty($this->const_mesAction)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'ACTION');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_mesAction = $retour;
        } else {
            $retour = $this->const_mesAction;
        }
        return $retour;
    }

    private function getPositionsContants()
    {
        $retour = [];
        if (empty($this->const_mesPosition)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'POSITION');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_mesPosition = $retour;
        } else {
            $retour = $this->const_mesPosition;
        }
        return $retour;
    }

    private function getTypesContants()
    {
        $retour = [];
        if (empty($this->const_mesType)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TYPE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_mesType = $retour;
        } else {
            $retour = $this->const_mesType;
        }
        return $retour;
    }

    private function getSizesContants()
    {
        $retour = [];
        if (empty($this->const_mesSize)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'SIZE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_mesSize = $retour;
        } else {
            $retour = $this->const_mesSize;
        }
        return $retour;
    }
}
