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
 * __set(string $key, $val)
 * enaDinstinctMessage()
 * disDinstinctMessage()
 * validate_notification_iconSource($val)
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
                $val = $this->validate_By_Constants($val, "NOTIFICATIONTYPE_", self::NOTIFICATIONTYPE_INFO);
                break;
            case 'size':
                $val = $this->validate_By_Constants($val, "NOTIFICATIONSIZE_", self::NOTIFICATIONSIZE_NORMAL);
                break;
            case 'action':
                $val = $this->validate_By_Constants($val, "NOTIFICATIONACTION_", self::NOTIFICATIONACTION_INIT);
                break;
            case 'position':
                $val = $this->validate_By_Constants($val, "NOTIFICATIONPOSITION_", self::NOTIFICATIONPOSITION_BR);
                break;
            case 'title':
            case 'body':
            case 'soundExt':
            case 'soundPath':
                $val = (string) $val;
                if ($val !== $this->truepath($val)) {
                    throw new \UnexpectedValueException("cheamin d'accès aux notifications sonores inexistant");
                }
                break;
            case 'showClass':
                $val = $this->validate_By_Constants($val, "NOTIFICATIONSHOW_", self::NOTIFICATIONSHOW_ZOOMIN);
                break;
            case 'hideClass':
                $val = $this->validate_By_Constants($val, "NOTIFICATIONHIDE_", self::NOTIFICATIONHIDE_ZOOMOUT);
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
        return in_array($val, $this->getConstantsGroup("NOTIFICATIONICON_"), true) ? $val : self::NOTIFICATIONICON_BOOTSTRAP;
    }
}