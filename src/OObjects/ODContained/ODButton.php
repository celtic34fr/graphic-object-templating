<?php


namespace GraphicObjectTemplating\OObjects\ODContained;


use Exception;
use GraphicObjectTemplating\OObjects\ODContained;
use InvalidArgumentException;
use ReflectionException;
use UnexpectedValueException;

/**
 * Class ODButton
 * @package GraphicObjectTemplating\OObjects\ODContained
 *
 * méthodes
 * --------
 * __construct(string $id)
 * constructor($id, $properties) : array
 * __isset(string $key) : bool
 * __get(string $key)
 * __set(string $key, $val)
 * getTypeConstants()
 * getNatureConstants()
 * getLinkTargetConstants()
 * validate_bType($val)
 * validate_bNature($val)
 */
class ODButton extends ODContained
{
    const BUTTONTYPE_CUSTOM = 'custom';
    const BUTTONTYPE_SUBMIT = 'submit';
    const BUTTONTYPE_RESET = 'reset';
    const BUTTONTYPE_LINK = 'link';

    const BUTTONNATURE_DEFAULT = 'btn btn-default';
    const BUTTONNATURE_PRIMARY = 'btn btn-primary';
    const BUTTONNATURE_SUCCESS = 'btn btn-success';
    const BUTTONNATURE_INFO = 'btn btn-info';
    const BUTTONNATURE_WARNING = 'btn btn-warning';
    const BUTTONNATURE_DANGER = 'btn btn-danger';
    const BUTTONNATURE_LINK = 'btn btn-link';
    const BUTTONNATURE_BLACK = 'btn btn-black';
    const BUTTONNATURE_CUSTOM = 'btn btn-custom';

    const BUTTONLINK_TARGET_BLANK = '_blank';
    const BUTTONLINK_TARGET_SELF = '_self';
    const BUTTONLINK_TARGET_PARENT = '_parent';
    const BUTTONLINK_TARGET_TOP = '_top';

    const EVENT_CLICK = 'click';
    const EVENT_HOVER = 'hover';

    private static array $const_type;
    private static array $const_nature;
    private static array $const_linkTarget;

    /**
     * ODInput constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__ . '/../../../params/oobjects/odcontained/odbutton/odbutton.config.php';
        $properties = require $path;
        parent::__construct($id, $properties);

        $properties = $this->object_contructor($id, $properties);
        $this->properties = $properties;

        if ((int)$this->widthBT === 0) {
            $this->widthBT = 12;
        }
    }

    /**
     * @param string $key
     * @return mixed|void|null
     */
    public function __get(string $key)
    {
        if ($key == 'image') {
            return parent::__get('pathFile');
        }
        return parent::__get($key);
    }

    /**
     * @param string $key
     * @param $val
     * @return mixed|void|null
     */
    public function __set(string $key, $val)
    {
        switch ($key) {
            case 'type':
                $val = $this->validate_bType($val);
                $callback = $this->event ? array_key_exists('click', $this->event) : false;

                switch ($this->type) {
                    case self::BUTTONTYPE_LINK:
                        if (!empty($this->form)) {
                            $this->form = '';
                        }
                        if ($callback) {
                            $events = $this->event;
                            $click = $events['click'];
                            $method = $click['method'];
                            if (!is_array($method)) {
                                $method = explode('|', $method);
                                $params = [];
                                foreach ($method as $item) {
                                    $item = explode(':', $item);
                                    $params[$item[0]] = $item[1];
                                }
                                $method = $params;
                                $click['method'] = $method;
                                $events['click'] = $click;
                                $this->event = $events;
                            }
                        }
                        break;
                    case self::BUTTONTYPE_RESET:
                        if (!empty($this->form)) {
                            $this->type = self::BUTTONTYPE_CUSTOM;
                        }
                        break;
                    case self::BUTTONTYPE_SUBMIT:
                        if ($callback && !empty($this->form)) {
                            $this->type = self::BUTTONTYPE_CUSTOM;
                        }
                        break;
                    default:
                        throw new UnexpectedValueException('Unexpected value');
                }
                break;
            case 'nature':
                $val = $this->validate_bNature($val);
                break;
            case 'label':
            case 'icon':
                $val = (string)$val;
                break;
            case 'image':
                $val = (string)$val;
                $key = 'pathFile';
                if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/" . $val)) {
                    throw new InvalidArgumentException("Fichier image inexistant (" . $val . ")");
                }
                $val = 'http://' . $_SERVER['HTTP_HOST'] . "/" . $val;
                break;
            case 'form':
                $val = (string)$val;
                $callback = array_key_exists('click', $this->event);
                $i = $this->type;
                if ($i == self::BUTTONTYPE_LINK) {
                    if (!empty($this->form)) {
                        $val = null;
                    }
                } else {
                    $this->type = $callback ? self::BUTTONTYPE_SUBMIT : self::BUTTONTYPE_CUSTOM;
                }
                break;
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
    private function getTypeConstants()
    {
        $retour = [];
        if (empty(self::$const_type)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'BUTTONTYPE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_type = $retour;
        } else {
            $retour = self::$const_type;
        }
        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getNatureConstants()
    {
        $retour = [];
        if (empty(self::$const_nature)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'BUTTONNATURE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_nature = $retour;
        } else {
            $retour = self::$const_nature;
        }
        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getLinkTargetConstants()
    {
        $retour = [];
        if (empty(self::$const_linkTarget)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'BUTTONLINK_TARGET');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_linkTarget = $retour;
        } else {
            $retour = self::$const_linkTarget;
        }
        return $retour;
    }

    /**
     * @param $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_bType($val)
    {
        return in_array($val, $this->getTypeConstants(), true) ? $val : self::BUTTONTYPE_CUSTOM;
    }

    /**
     * @param $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_bNature($val)
    {
        return in_array($val, $this->getNatureConstants(), true) ? $val : self::BUTTONNATURE_DEFAULT;
    }
}