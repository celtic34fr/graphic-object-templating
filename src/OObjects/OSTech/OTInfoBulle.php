<?php


namespace GraphicObjectTemplating\OObjects\OSTech;


use Exception;
use GraphicObjectTemplating\OObjects\OObject;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use UnexpectedValueException;

/**
 * Class OTInfoBulle
 * @package GraphicObjectTemplating\OObjects
 *
 * méthodes
 * --------
 * __construct(array $properties)
 * __set(string $key, $val)
 * __get($key)
 * __isset(string $key)
 *
 * méthodes privées
 * ----------------
 * validate_properties(array $properties)
 * validate_type($val)
 * validate_placement($val)
 * validate_trigger($val)
 * getConstants(): array
 * getConstantsGroup(): array
 * validate_By_Constants(): array
 * 
 * TODO remplacer les 3 dernières méthodes par les appel à validate_By_Constants()
 */
class OTInfoBulle
{
    /**
     * @var array $properties
     */
    public $properties;

    const IBTYPE_TOOLTIP = 'tooltip';
    const IBTYPE_POPOVER = 'popover';

    const IBPLACEMENT_TOP = 'top';
    const IBPLACEMENT_BOTTOM = 'bottom';
    const IBPLACEMENT_LEFT = 'left';
    const IBPLACEMENT_RIGHT = 'right';
    const IBPLACEMENT_AUTO = 'auto';

    const IBTRIGGER_CLICK = 'click';
    const IBTRIGGER_HOVER = 'hover';
    const IBTRIGGER_FOCUS = 'focus';
    const IBTRIGGER_MANUEL = 'manuel';

    const FIELDS = ['setIB' => false, 'type' => self::IBTYPE_TOOLTIP, 'animation' => true, 'delay_show' => 500,
        'delay_hide' => 100, 'html'=>OObject::BOOLEAN_FALSE, 'placement'=>self::IBPLACEMENT_TOP, 'title'=>null,
        'content'=>null, 'trigger'=>self::IBTRIGGER_HOVER];

    /**
     * @var array|mixed|null
     */
    private $const_type;
    /**
     * @var array|mixed|null
     */
    private $const_placement;

    protected static array $const_global;

    /**
     * OTInfoBulle constructor.
     * @param array $properties
     */
    public function __construct(array $properties = [])
    {
        if ($properties = $this->validate_properties($properties)) {
            $this->properties = $properties;
        }
    }

    /**
     * @param string $key
     * @param $val
     * @return $this
     * @throws Exception
     */
    public function __set(string $key, $val)
    {
        switch ($key) {
            case 'setIB':
            case 'animation':
                $val = (bool)$val;
                break;
            case 'type':
                $val = $this->validate_type($val);
                break;
            case 'html':
                if (empty($val) or ($val === 0)) {
                    $val = OObject::BOOLEAN_FALSE;
                } else {
                    $val = OObject::BOOLEAN_TRUE;
                }
                $val = (in_array($val, [OObject::BOOLEAN_FALSE, OObject::BOOLEAN_TRUE])) ? $val : (bool)$val;
                break;
            case 'placement':
                $val = $this->validate_placement($val);
                break;
            case 'trigger':
                $val = $this->validate_trigger($val);
                break;
            case 'title':
            case 'content':
                $val = (string)$val;
                break;
            case 'delay_show':
                $val = (int)$val > 0 ? (int)$val : 500;
                break;
            case 'delay_hide':
                $val = (int)$val > 0 ? (int)$val : 100;
                break;
            default:
                throw new InvalidArgumentException("Attribut " . $key . ' invalide (OTInfoBulle)');
        }

        $this->properties[$key] = $val;
        return $this;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        return $this->properties[$key] ?? null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key)
    {
        return array_key_exists($key, $this->properties);
    }

    /**
     * @param $properties
     * @return mixed|string
     */
    public function validate_properties(array $properties)
    {
        $return_properties = [];
        foreach (self::FIELDS as $key=>$val) {
            if (!array_key_exists($key, $properties)) {
                $return_properties[$key] = $val;
            } else {
                $return_properties[$key] = $properties[$key];
                unset($properties[$key]);
            }
        }
        if (count($properties)) {
            throw new UnexpectedValueException("Attributs incompatibles avec OTInfoBulle");
        }
        return $return_properties;
    }


    /**
     * @param $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_type($val)
    {
        $val = $this->validate_By_Constants($val, "IBTYPE_", self::IBTYPE_TOOLTIP);
        return $val;
    }

    /**
     * @param $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_placement($val)
    {
        $val = $this->validate_By_Constants($val, "IBPLACEMENT_", self::IBPLACEMENT_TOP);
        return $val;
    }

    /**
     * @param $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_trigger($val)
    {
        $val = $this->validate_By_Constants($val, "IBTRIGGER_", self::IBTRIGGER_HOVER);
        return $val;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getConstants(): array
    {
        self::$const_global = [];
        if (empty(self::$const_global)) {
            $reflectionClass = new ReflectionClass(static::class);
            if ($reflectionClass && in_array('getConstants', get_class_methods($reflectionClass))) {
                self::$const_global = $reflectionClass->getConstants();
            }
        }
        return self::$const_global;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getConstantsGroup(string $prefix): array
    {
        $constants = self::getConstants();
        $retour = [];
        foreach ($constants as $key => $constant) {
            if (strpos($key, $prefix) !== false) {
                $retour[$key] = $constant;
            }
        }
        return $retour;
    }

    /**
     * @param $val
     * @param string $cle_contants
     * @param $default
     * @return mixed
     */
    public function validate_By_Constants($val, string $cle_contants, $default)
    {
        return (in_array($val, $this->getConstantsGroup($cle_contants), true)) ? $val : $default;
    }
}