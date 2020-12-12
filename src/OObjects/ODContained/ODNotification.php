<?php


namespace GraphicObjectTemplating\OObjects\ODContained;


use Exception;
use GraphicObjectTemplating\OObjects\ODContained;
use GraphicObjectTemplating\OObjects\OObject;
use InvalidArgumentException;
use ReflectionException;

/**
 * Class ODNotification
 * @package GraphicObjectTemplating\OObjects\ODContained
 *
 * méthodes
 * --------
 * __construct(string $id)
 * constructor($id, $properties) : array
 * __isset(string $key) : bool
 * __get(string $key)
 * __set(string $key, $val)
 * getActionsConstants() : array
 * getPositionsConstants() : array
 * getTypesConstants() : array
 * getSizesConstants() : array
 * getShowsConstants() : array
 * getHidesConstants() : array
 * validate_notification_type(string $val)
 * validate_notification_size(string $val)
 * validate_notification_action(string $val)
 * validate_notification_position(string $val)
 * validate_notification_show(string $val)
 * validate_notification_hide(string $val)
 * enaDinstinctMessage()
 * disDinstinctMessage()
 */
class ODNotification extends ODContained
{

    const NOTIFICATIONACTION_INIT   = 'init';
    const NOTIFICATIONACTION_SEND   = 'send';

    const NOTIFICATIONPOSITION_BR   = 'bottom right';
    const NOTIFICATIONPOSITION_BL   = 'bottom left';
    const NOTIFICATIONPOSITION_TR   = 'top right';
    const NOTIFICATIONPOSITION_TL   = 'top left';

    const NOTIFICATIONTYPE_INFO     = 'info';
    const NOTIFICATIONTYPE_SUCCESS  = 'success';
    const NOTIFICATIONTYPE_WARNING  = 'warning';
    const NOTIFICATIONTYPE_ERROR    = 'error';

    const NOTIFICATIONSIZE_MINI     = 'mini';
    const NOTIFICATIONSIZE_NORMAL   = 'normal';
    const NOTIFICATIONSIZE_LARGE    = 'large';

    const NOTIFICATIONSHOW_ZOOMIN       = 'zoomIn';
    const NOTIFICATIONSHOW_ZOOMINUP     = 'zoomInUp';
    const NOTIFICATIONSHOW_BOUNCEIN     = 'bounceIn';
    const NOTIFICATIONSHOW_FADEINDOWN   = 'fadeInDown';
    const NOTIFICATIONSHOW_ROLLIN       = 'rollIn';

    const NOTIFICATIONHIDE_ZOOMOUT      = 'zoomOut';
    const NOTIFICATIONHIDE_ZOOMOUTDOWN  = 'zoomOutDown';
    const NOTIFICATIONHIDE_BOUNCEOUT    = 'bounceOut';
    const NOTIFICATIONHIDE_FADEINDOWN   = 'fadeUpDown';
    const NOTIFICATIONHIDE_ROLLOUT      = 'rollOut';

    const NOTIFICATIONICON_BOOTSTRAP    = 'bootstrap';
    const NOTIFICATIONICON_FONTAWESOME  = 'fontAwesome';

    private array $const_NotificationAction;
    private array $const_NotificationPosition;
    private array $const_NotificationType;
    private array $const_NotificationSize;
    private array $const_NotificationShow;
    private array $const_NotificationHide;
    private array $const_NotificationIcon;

    /**
     * ODInput constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__. '/../../../params/oobjects/odcontained/odnotification/odnotification.config.php';
        $properties = require $path;
        parent::__construct($id, $properties);

        $properties = $this->object_contructor($id, $properties);
        $this->properties = $properties;

        if ((int)$this->widthBT ===0 ) {
            $this->widthBT = 12;
        }
    }

    /**
     * @param string $key
     * @param $val
     * @return mixed|void|null
     */
    public function __set(string $key, $val)
    {
        switch ($key) {
            case 'delay':
            case 'delayMessage':
                if (!is_numeric($val)) {
                    throw new InvalidArgumentException("paramètre de délais non numérique");
                }
                $val = (int) $val;
                break;
            case 'type':
                $val = $this->validate_notification_type($val);
                break;
            case 'size':
                $val = $this->validate_notification_size($val);
                break;
            case 'action':
                $val = $this->validate_notification_action($val);
                break;
            case 'position':
                $val = $this->validate_notification_position($val);
                break;
            case 'title':
            case 'body':
            case 'soundExt':
            case 'soundPath': // TODO voir comment valider le path
                $val = (string) $val;
                break;
            case 'showClass':
                $val = $this->validate_notification_show($val);
                break;
            case 'hideClass':
                $val = $this->validate_notification_hide($val);
                break;
            case 'width':
            case 'height':
                if ($val !== 'auto') {
                    if (!is_numeric($val)) {
                        throw new InvalidArgumentException("paramètre $key non numérique");
                    }
                    $val = (int) $val;
                }
            case 'icon':
            case 'delayIndicator':
            case 'sound':
            case 'showAfterPrevious':
            case 'closable':
            case 'closeOnClick':
                $val = (bool) $val;
                $val = $val ? OObject::BOOLEAN_TRUE : OObject::BOOLEAN_FALSE;
                break;
            case 'iconSource':
                $val = $this->validate_notification_iconSource($val);
            default:
                return parent::__set($key, $val);
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getActionsConstants() : array
    {
        $retour = [];
        if (empty($this->const_NotificationAction)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'NOTIFICATIONACTION_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_NotificationAction = $retour;
        } else {
            $retour = $this->const_NotificationAction;
        }
        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getPositionsConstants() : array
    {
        $retour = [];
        if (empty($this->const_NotificationPosition)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'NOTIFICATIONPOSITION_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_NotificationPosition = $retour;
        } else {
            $retour = $this->const_NotificationPosition;
        }
        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getTypesConstants() : array
    {
        $retour = [];
        if (empty($this->const_NotificationType)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'NOTIFICATIONTYPE_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_NotificationType = $retour;
        } else {
            $retour = $this->const_NotificationType;
        }
        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getSizesConstants() : array
    {
        $retour = [];
        if (empty($this->const_NotificationSize)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'NOTIFICATIONSIZE_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_NotificationSize = $retour;
        } else {
            $retour = $this->const_NotificationSize;
        }
        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getShowsConstants() : array
    {
        $retour = [];
        if (empty($this->const_NotificationShow)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'NOTIFICATIONSHOW_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_NotificationShow = $retour;
        } else {
            $retour = $this->const_NotificationShow;
        }
        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getHidesConstants() : array
    {
        $retour = [];
        if (empty($this->const_NotificationHide)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'NOTIFICATIONHIDE_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_NotificationHide = $retour;
        } else {
            $retour = $this->const_NotificationHide;
        }
        return $retour;
    }

    /**
     * @param string $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_notification_type(string $val)
    {
        return in_array($val, $this->getTypesConstants(), true) ? $val : self::NOTIFICATIONTYPE_INFO;
    }

    /**
     * @param string $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_notification_size(string $val)
    {
        return in_array($val, $this->getSizesConstants(), true) ? $val : self::NOTIFICATIONSIZE_NORMAL;
    }

    /**
     * @param string $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_notification_action(string $val)
    {
        return in_array($val, $this->getActionsConstants(), true) ? $val : self::NOTIFICATIONACTION_INIT;
    }

    /**
     * @param string $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_notification_position(string $val)
    {
        return in_array($val, $this->getPositionsConstants(), true) ? $val : self::NOTIFICATIONPOSITION_BR;
    }

    /**
     * @param string $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_notification_show(string $val)
    {
        return in_array($val, $this->getShowsConstants(), true) ? $val : self::NOTIFICATIONSHOW_ZOOMIN;
    }

    /**
     * @param string $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_notification_hide(string $val)
    {
        return in_array($val, $this->getHidesConstants(), true) ? $val : self::NOTIFICATIONHIDE_ZOOMOUT;
    }

    /**
     * enable distinct messsage
     */
    public function enaDinstinctMessage()
    {
        $this->showAfterPrevious = true;
    }

    /**
     * disable distinct message
     */
    public function disDinstinctMessage()
    {
        $this->showAfterPrevious = false;
    }

    /**
     * @param $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_notification_iconSource($val)
    {
        return in_array($val, $this->getIconSourcesConstants(), true) ? $val : self::NOTIFICATIONICON_BOOTSTRAP;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getIconSourcesConstants() : array
    {
        $retour = [];
        if (empty($this->const_NotificationIcon)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'NOTIFICATIONICON');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_NotificationIcon = $retour;
        } else {
            $retour = $this->const_NotificationIcon;
        }
        return $retour;
    }
}