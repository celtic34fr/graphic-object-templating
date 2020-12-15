<?php


namespace GraphicObjectTemplating\OObjects\ODContained;


use Exception;
use GraphicObjectTemplating\OObjects\ODContained;
use InvalidArgumentException;
use ReflectionException;
use UnexpectedValueException;

/**
 * Class ODInput
 * @package GraphicObjectTemplating\OObjects\ODContained
 *
 * méthodes
 * --------
 * __construct(string $id)
 * constructor($id, $properties) : array
 * __isset(string $key) : bool
 * __get(string $key)
 * __set(string $key, $val)
 * validate_iType($val) : string
 * getTypeConstants(): array
 */
class ODInput extends ODContained
{
    const INPUTTYPE_HIDDEN = 'hidden';
    const INPUTTYPE_TEXT = 'text';
    const INPUTTYPE_PASSWORD = 'password';
    const INPUTTYPE_NUMBER = 'number';
    const INPUTTYPE_EMAIL = 'email';

    const EVENT_CHANGE   	= 'change';
    const EVENT_KEYPRESS   	= 'keypress';
    const EVENT_KEYUP       = 'keyup';

    const ERR_UNEXPECTED_VALUE_MSG = "Unexpected value";

    private static $const_type;

    /**
     * ODInput constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__ . '/../../../params/oobjects/odcontained/odinput/odinput.config.php';
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
     * @throws Exception
     */
    public function __set(string $key, $val)
    {
        $properties = $this->properties;
        switch ($key) {
            case 'type':
                $val = $this->validate_iType($val);
                break;
            case 'autoFocus':
                $val = (bool)$val;
                break;
            case 'minLength':
                $val = (int)$val;
                if (!array_key_exists('maxLength', $this->properties)) {
                    throw new UnexpectedValueException("Stucture objet ODInput altérée, manque maxLength");
                } elseif ((int)$this->properties['maxLength'] < $val) {
                    throw new InvalidArgumentException("taille maxi (" . $this->properties['maxLength'] . ") inférieure à taille mini (" . $val . ")");
                }
                $this->properties['minLength'] = $val;
                break;
            case 'maxLength':
                $val = (int)$val;
                if (!array_key_exists('minLength', $this->properties)) {
                    throw new UnexpectedValueException("Stucture objet ODInput altérée, manque minLength");
                } elseif ((int)$this->properties['minLength'] > $val) {
                    throw new InvalidArgumentException("taille mini (" . $this->properties['minLength'] . ") supérieure à taille maxi (" . $val . ")");
                }
                $this->properties['maxLength'] = $val;
                break;
            case 'labelWidthBT':
                $inputWidthBT = 0;
                if (is_string($val) && strtolower($val[0]) === 'w') {
                    $val = (int)substr($val, 1);
                }
                if (is_numeric($val) and ($val > 12)) {
                    $val = 12;
                }

                $inputWidthBT = 12 - (int)$val;
                $val = $this->validate_widthBT('W'.$val);
                $properties['inputWidthBT'] = $this->validate_widthBT('W'.$inputWidthBT);
                break;
            case 'inputWidthBT':
                $labelWidthBT = 0;
                if (is_string($val) && strtolower($val[0]) === 'w') {
                    $val = (int)substr($val, 1);
                }
                if (is_numeric($val) and ($val > 12)) {
                    $val = 12;
                }

                $labelWidthBT = 12 - (int)$val;
                $val = $this->validate_widthBT($val);
                $properties['labelWidthBT'] = $this->validate_widthBT($labelWidthBT);
                break;
            case 'reveal_pwd':
                $val = (bool)$val;
                if ($properties['type'] !== self::INPUTTYPE_PASSWORD) {$val = false;}
				break;
            default:
                return parent::__set($key, $val);
        }
        $properties[$key] = $val;
        $this->properties = $properties;
        return true;
    }

    /**
     * @param $val
     * @return string
     */
    private function validate_iType($val) : string
    {
        return (in_array($val, $this->getTypeConstants())) ? $val : self::INPUTTYPE_TEXT;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getTypeConstants(): array
    {
        $retour = [];
        if (empty(self::getConstants())) {
            $this->constants = self::getConstants();
        }
        if (empty(self::$const_type)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'INPUTTYPE');
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
}