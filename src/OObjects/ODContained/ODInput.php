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
 * __set(string $key, $val)
 */
class ODInput extends ODContained
{
    const INPUTTYPE_HIDDEN = 'hidden';
    const INPUTTYPE_TEXT = 'text';
    const INPUTTYPE_PASSWORD = 'password';
    const INPUTTYPE_NUMBER = 'number';
    const INPUTTYPE_EMAIL = 'email';
    const INPUTTYPE_DATE = 'date';

    const EVENT_CHANGE = 'change';
    const EVENT_KEYPRESS = 'keypress';
    const EVENT_KEYUP = 'keyup';

    const ERR_UNEXPECTED_VALUE_MSG = "Unexpected value";

    protected static $obinput_attributes = ['type', 'size', 'minLength', 'maxLength', 'label', 'placeholder',
        'labelWidthBT', 'inputWidthBT', 'autoFocus', 'mask', 'valMin', 'valMax', 'reveal_pwd'];

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

        if ((int)$this->widthBT === 0) {
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
        if (!array_key_exists($key, $this->properties)) {
            throw new UnexpectedValueException("Stucture objet ODInput altérée, manque attribut $key");
        }
        switch ($key) {
            case 'type':
                $val = $this->validate_By_Constants($val, "INPUTTYPE_", self::INPUTTYPE_TEXT);
                break;
            case 'autoFocus':
                $val = (bool)$val;
                break;
            case 'maxLength':
            case 'minLength':
                $val = (int)$val;
                if (((int)$this->properties['maxLength'] < $val) or ((int)$this->properties['minLength'] > $val)) {
                    throw new InvalidArgumentException("taille $val doit être comprise entre " . $this->properties['maxLength'] . " et " . $this->properties['minLength'] . ")");
                }
                break;
            case 'labelWidthBT':
            case 'inputWidthBT':
                if (is_string($val) && strtolower($val[0]) === 'w') {
                    $val = (int)substr($val, 1);
                }
                if (is_numeric($val) and ($val > 12)) {
                    $val = 12;
                }

                $autreWidthBT = ($key === 'labelWidthBT') ? 'labelWidthBT' : 'inputWidthBT';
                $autreWidthVal = 12 - (int)$val;
                $val = $this->validate_widthBT('W' . $val);
                $properties[$autreWidthBT] = $this->validate_widthBT('W' . $autreWidthVal);
                break;
            case 'reveal_pwd':
                $val = ($properties['type'] !== self::INPUTTYPE_PASSWORD) ? flase : (bool)$val;
                break;
            default:
                return parent::__set($key, $val);
        }
        $properties[$key] = $val;
        $this->properties = $properties;
        return true;
    }
}