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
 * __get(string $key)
 * __set(string $key, $val)
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
        if (!$key) { return false; }

        if ($key === 'type') {
            $val = $this->validate_By_Constants($val, "BUTTONTYPE_", self::BUTTONTYPE_CUSTOM);
            $this->alter_event_on_callback($this->event and array_key_exists('click', $this->event));
        } elseif ($key === 'nature') {
            $val = $this->validate_By_Constants($val, "BUTTONNATURE_", self::BUTTONNATURE_DEFAULT);
        } elseif ($key === 'image') {
            $key = 'pathFile';
            if (!file_exists($_SERVER["DOCUMENT_ROOT"] . "/" . $val)) {
                throw new InvalidArgumentException("Fichier image inexistant (" . $val . ")");
            }
            $val = 'http://' . $_SERVER['HTTP_HOST'] . "/" . $val;
        } elseif ($key === 'form') {
            $callback = array_key_exists('click', $this->event);
            $i = $this->type;
            if ($i == self::BUTTONTYPE_LINK) {
                $val = null;
            } else {
                $this->type = $callback ? self::BUTTONTYPE_SUBMIT : self::BUTTONTYPE_CUSTOM;
            }
        } else {
            return parent::__set($key, $val);
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @param bool $callback
     */
    private function alter_event_on_callback(bool $callback)
    {
        switch ($this->type) {
            case self::BUTTONTYPE_LINK:
                $this->form = '';
                if (!$callback) { break;}

                $events = $this->event;
                $method = $events['click']['method'];
                if (is_array($method)) { break; }

                $methodArray = explode('|', $method);
                $method = [];
                foreach ($methodArray as $item) {
                    $item = explode(':', $item);
                    $method[$item[0]] = $item[1];
                }
                $events['click']['method'] = $method;
                $this->event = $events;
                break;
            case self::BUTTONTYPE_RESET:
                $this->type = (empty($this->form)) ? $this->type : self::BUTTONTYPE_CUSTOM;
                break;
            case self::BUTTONTYPE_SUBMIT:
                if ($callback and !empty($this->form)) {
                    $this->type = self::BUTTONTYPE_CUSTOM;
                }
                break;
            default:
                throw new UnexpectedValueException('Unexpected value');
        }
    }
}