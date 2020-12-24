<?php


namespace GraphicObjectTemplating\OObjects\OSTech;


use Exception;
use InvalidArgumentException;
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
 * getTypeConstants(): array
 * getPlacementConstants(): array
 * getTriggerConstants(): array
 * 
 * TODO remplacer les 3 dernières méthodes par les appel à validate_By_Constants()
 */
class OTInfoBulle
{
    /**
     * @var array $properties
     */
    private $properties;

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

    /**
     * OTInfoBulle constructor.
     * @param array $properties
     */
    public function __construct(array $properties)
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
                $val = (bool)$val;
                $val = $val ? OObject::BOOLEAN_TRUE : OObject::BOOLEAN_FALSE;
                break;
            case 'placement':
                $val = $this->validate_placement($val);
                break;
            case 'trigger':
                $val = $this->validate_trigger($val);
                break;
            case 'tile':
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
            throw new UnexpectedValueException("Attributs impcompatibles avec OTInfoBulle");
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
        return in_array($val, $this->getTypeConstants()) ? $val : self::IBTYPE_TOOLTIP;
    }

    /**
     * @param $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_placement($val)
    {
        return in_array($val, $this->getPlacementConstants()) ? $val : self::IBPLACEMENT_TOP;
    }

    /**
     * @param $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_trigger($val)
    {
        return in_array($val, $this->getTriggerConstants()) ? $val : self::IBTRIGGER_HOVER;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getTypeConstants(): array
    {
        $retour = [];
        if (empty($this->constants)) {
            $this->constants = OObject::getConstants();
        }
        if (empty($this->const_display)) {
            foreach ($this->constants as $key => $constant) {
                $pos = strpos($key, 'IDTYPE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_type = $retour;
        } else {
            $retour = $this->const_type;
        }

        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getPlacementConstants(): array
    {
        $retour = [];
        if (empty($this->constants)) {
            $this->constants = OObject::getConstants();
        }
        if (empty($this->const_display)) {
            foreach ($this->constants as $key => $constant) {
                $pos = strpos($key, 'IDPLACEMENT');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_placement = $retour;
        } else {
            $retour = $this->const_placement;
        }

        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getTriggerConstants(): array
    {
        $retour = [];
        if (empty($this->constants)) {
            $this->constants = OObject::getConstants();
        }
        if (empty($this->const_display)) {
            foreach ($this->constants as $key => $constant) {
                $pos = strpos($key, 'IDTRIGGER');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_trigger = $retour;
        } else {
            $retour = $this->const_trigger;
        }

        return $retour;
    }
}