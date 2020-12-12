<?php


namespace GraphicObjectTemplating\OObjects;


use BadFunctionCallException;
use BadMethodCallException;
use Exception;
use GraphicObjectTemplating\OObjects\ODContained\ODButton;
use GraphicObjectTemplating\OObjects\OSTech\OTInfoBulle;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use UnexpectedValueException;

/**
 * Class OObject
 * @package GraphicObjectTemplating\OObjects
 *
 * méthodes
 * --------
 * __construct(string $id, array $properties)
 * constructor(string $id, array $properties) : array
 * __get(string $key)
 * __isset(string $key) : bool
 * __set(string $key, $val)
 * validate_display(string $val): string
 * validate_widthBT($val)
 * set_Termplate_ClassName($typeObj, $object, $template): array
 * merge_properties(array $add_properties, array $properties) : array
 * getEvent($key)
 * setEvent(string $key, array $parms) : bool
 * issetEvent($key)
 * validate_event(string $key)
 * validate_event_parms(array $parms) : array
 * formatEvent(string $class, string $method, bool $stopEvent) : array
 * static getConstants() : array
 * getDisplayConstants(): array
 * getEventConstants() : array
 * getCssColorConstants() : array
 */
class OObject
{
    public $id;
    public $properties;

    const DISPLAY_NONE = 'none';
    const DISPLAY_BLOCK = 'block';
    const DISPLAY_INLINE = 'inline';
    const DISPLAY_INBLOCK = 'inline-block';
    const NO_DISPLAY = 'noDisplay';

    const BOOLEAN_TRUE = 'true';
    const BOOLEAN_FALSE = 'false';

    const CSS_COLOR_BLACK = 'black';
    const CSS_COLOR_WHITE = 'white';
    const CSS_COLOR_LIME = 'lime';
    const CSS_COLOR_GREEN = 'green';
    const CSS_COLOR_EMERALD = 'emerald';
    const CSS_COLOR_TEAL = 'teal';
    const CSS_COLOR_BLUE = 'blue';
    const CSS_COLOR_CYAN = 'cyan';
    const CSS_COLOR_COBALT = 'cobalt';
    const CSS_COLOR_INDIGO = 'indigo';
    const CSS_COLOR_VIOLET = 'violet';
    const CSS_COLOR_PINK = 'pink';
    const CSS_COLOR_MAGENTA = 'magenta';
    const CSS_COLOR_CRIMSON = 'crimson';
    const CSS_COLOR_RED = 'red';
    const CSS_COLOR_ORANGE = 'orange';
    const CSS_COLOR_AMBER = 'amber';
    const CSS_COLOR_YELLOW = 'yellow';
    const CSS_COLOR_BROWN = 'brown';
    const CSS_COLOR_OLIVE = 'olive';
    const CSS_COLOR_STEEL = 'steel';
    const CSS_COLOR_MAUVE = 'mauve';
    const CSS_COLOR_TAUPE = 'taupe';
    const CSS_COLOR_GRAY = 'gray';
    const CSS_COLOR_DARK = 'dark';
    const CSS_COLOR_DARKER = 'darker';
    const CSS_COLOR_DARKBROWN = 'darkBrown';
    const CSS_COLOR_DARKCRIMSON = 'darkCrimson';
    const CSS_COLOR_DARKMAGENTA = 'darkMagenta';
    const CSS_COLOR_DARKINDIGO = 'darkIndigo';
    const CSS_COLOR_DARKCYAN = 'darkCyan';
    const CSS_COLOR_DARKCOBALT = 'darkCobalt';
    const CSS_COLOR_DARKTEAL = 'darkTeal';
    const CSS_COLOR_DARKEMERALD = 'darkEmerald';
    const CSS_COLOR_DARKGREEN = 'darkGreen';
    const CSS_COLOR_DARKORANGE = 'darkOrange';
    const CSS_COLOR_DARKRED = 'darkRed';
    const CSS_COLOR_DARKPINK = 'darkPink';
    const CSS_COLOR_DARKVIOLET = 'darkViolet';
    const CSS_COLOR_DARKBLUE = 'darkBlue';
    const CSS_COLOR_LIGHTBLUE = 'lightBlue';
    const CSS_COLOR_LIGHTRED = 'lightRed';
    const CSS_COLOR_LIGHTGREEN = 'lightGreen';
    const CSS_COLOR_LIGHTERBLUE = 'lighterBlue';
    const CSS_COLOR_LIGHTTEAL = 'lightTeal';
    const CSS_COLOR_LIGHTOLIVE = 'lightOlive';
    const CSS_COLOR_LIGHTORANGE = 'lightOrange';
    const CSS_COLOR_LIGHTPINK = 'lightPink';
    const CSS_COLOR_GRAYDARK = 'grayDark';
    const CSS_COLOR_GRAYDARKER = 'grayDarker';
    const CSS_COLOR_GRAYLIGHT = 'grayLight';
    const CSS_COLOR_GRAYLIGHTER = 'grayLighter';


    protected static array $const_display;
    protected static array $const_event;
    protected static array $const_css_color;

    /**
     * OObject constructor.
     * @param string $id
     * @param array $properties
     */
    public function __construct(string $id, array $properties) {
        // TODO : revoir la mise en oeuvre et gestion des infoBulle qque soit l'objet GOT

        $this->properties = $this->constructor($id, $properties);
    }

    /**
     * @param string $id
     * @param array $properties
     * @return array
     */
    public function constructor(string $id, array $properties): array
    {
        $path = __DIR__ . '/../../params/oobjects/oobject.config.php';
        $this->id = $id;
        $oobj_properties = require $path;
        foreach ($oobj_properties as $key => $val) {
            if (!array_key_exists($key, $properties)) {
                $properties[$key] = $val;
            }
        }
        $properties['id'] = $id;
        return $properties;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function __get(string $key)
    {
        if (array_key_exists($key, $this->properties)) {
            return $this->properties[$key];
        }
        return false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return array_key_exists($key, $this->properties);
    }

    /**
     * @param string $key
     * @param $val
     * @return bool|mixed|null
     * @throws ReflectionException
     */
    public function __set(string $key, $val)
    {
        switch ($key) {
            case 'display':
                $val = $this->validate_display($val);
                break;
            case 'autoCenter':
            case 'state':
                $val = (bool)$val;
                break;
            case 'inforBulle':
                switch (true){
                    case (is_array($val)) :
    					$val = new OTInfoBulle($val);
    					break;
                    case !($val instanceOf OTInfoBulle) :
    					throw new UnexpectedValueException("Attribut OTInfoBulle valeur fournie incorrecte");
                        break;
                    default:
                        throw new BadFunctionCallException('Unexpected value');
                }
                break;
            case 'event':
                throw new BadFunctionCallException("Affectation directe d'évènement impossible, passer par méthodes adaptées");
                break;
            case 'widthBT':
                $val = $this->validate_widthBT($val);
                break;
            default:
                return array_key_exists($key, $this->properties) ? $this->properties[$key] = $val : null;
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @param string $val
     * @return string
     * @throws ReflectionException
     */
    public function validate_display(string $val): string
    {
        return in_array($val, $this->getDisplayConstants(), true) ? $val : self::DISPLAY_BLOCK;
    }

    /**
     * @param $val
     * @return false|string
     */
    protected function validate_widthBT($val)
    {
        // TODO: tester l'insertion d'offset
        // TODO: voir l'ajout des class CSS col-chiffre et offset-chiffre
        if (!empty($val)) {
            $ret = [];
            $lxs = $lsm = $lmd = $llg = 0;
            $ixs = $ism = $imd = $ilg = 0;

            if (is_numeric($val) && (int)$val <= 12) {
                $lxs = $lsm = $lmd = $llg = (int)$val;
            } else {
                foreach (explode(':', $val) as $item) {
                    $key = strtoupper($item);
                    switch (substr($key, 0, 2)) {
                        case 'WL':
                            $llg = (int)substr($key, 2);
                            break;
                        case 'WM':
                            $lmd = (int)substr($key, 2);
                            break;
                        case 'WS':
                            $lsm = (int)substr($key, 2);
                            break;
                        case 'WX':
                            $lxs = (int)substr($key, 2);
                            break;
                        case 'OL':
                            $ilg = (int)substr($key, 2);
                            break;
                        case 'OM':
                            $imd = (int)substr($key, 2);
                            break;
                        case 'OS':
                            $ism = (int)substr($key, 2);
                            break;
                        case 'OX':
                            $ixs = (int)substr($key, 2);
                            break;
                        default:
                            if ($key[0] == 'W') {
                                $llg = (int)substr($key, 1);
                                $lmd = $lsm = $lxs = $llg;
                            } elseif ($key[0] == 'O') {
                                $ilg = (int)substr($key, 1);
                                $imd = $ism = $ixs = $ilg;
                            }
                            break;
                    }
                }
            }

            if ($llg) {
                $ret['WL'] = 'WL' . $llg;
            }
            if ($ilg) {
                $ret['OL'] = 'OL' . $ilg;
            }
            if ($lmd) {
                $ret['WM'] = 'WM' . $lmd;
            }
            if ($imd) {
                $ret['OM'] = 'OM' . $imd;
            }
            if ($lsm) {
                $ret['WS'] = 'WS' . $lsm;
            }
            if ($ism) {
                $ret['OS'] = 'OS' . $ism;
            }
            if ($lxs) {
                $ret['WX'] = 'WX' . $lxs;
            }
            if ($ixs) {
                $ret['OX'] = 'OX' . $ixs;
            }

            if (!empty($ret)) {
                return implode(':', $ret);
            }
        }
        return false;
    }

    /**
     * @param $typeObj
     * @param $object
     * @param $template
     * @param $className
     * @return array
     */
    public function set_Termplate_ClassName($typeObj, $object, $template): array
    {
        if ($typeObj && $object && $template) {
            $templateName = 'graphicobjecttemplating/oobjects/' . $typeObj;
            $templateName .= '/' . $object . '/' . $template;

            $objName = 'GraphicObjectTemplating/OObjects/';
            $objName .= strtoupper(substr($typeObj, 0, 3));
            $objName .= strtolower(substr($typeObj, 3)) . '/';
            $objName .= strtoupper(substr($object, 0, 3));
            $objName .= strtolower(substr($object, 3));
            $objName = str_replace('/', chr(92), $objName);

            return [$templateName, $objName];
        }
        return [];
    }

    /**
     * @param $properties
     * @return array
     */
    public function merge_properties(array $add_properties, array $properties): array
    {
        foreach ($add_properties as $key => $val) {
            if (!array_key_exists($key, $properties)) {
                $properties[$key] = $val;
            }
        }
        return $properties;
    }

    /** --------------------- *
     * gestion des évènements *
     * ---------------------- */
    /**
     * @param $key
     * @return false|mixed
     */
    public function getEvent($key)
    {
        $events = $this->event;
        return array_key_exists($key, $events) ? $events[$key] : false;
    }

    /**
     * @param string $key
     * @param array $parms
     * @return bool
     * @throws Exception
     */
    public function setEvent(string $key, array $parms): bool
    {
        $validate_event = $this->validate_event($key);
        if ((bool)$validate_event !== false) {
            $parms = $this->validate_event_parms($parms);
            if ($parms) {
                $events = $this->properties['event'];
                $events[$key] = $parms;
                $this->properties['event'] = $events;
                return true;
            }
        }
        throw new InvalidArgumentException("Evènement " . $key . " tableau paramètres incorrects");
    }

    /**
     * @param $key
     * @return bool
     */
    public function issetEvent($key)
    {
        return array_key_exists($key, $this->properties['event']);
    }

    /**
     * @param string $key
     * @return false|string
     */
    private function validate_event(string $key)
    {
        return (in_array($key, $this->getEventConstants())) ? $key : false;
    }

    /**
     * @param array $parms
     * @return array
     * @throws Exception
     */
    protected function validate_event_parms(array $parms): array
    {
        $valid = array_key_exists('class', $parms) && $parms['class'];
        $valid = $valid && array_key_exists('method', $parms) && $parms['method'];
        $valid = $valid && array_key_exists('stopEvent', $parms) && is_bool($parms['stopEvent']);

        if ($valid) {
            $class = $parms['class'];
            $method = $parms['method'];

            switch (true) {
                case ($class === 'javascript:') :
                    break;
                case ($this->object === 'odbutton' && $this->type === ODButton::BUTTONTYPE_LINK):
                    $params = [];
                    if ($method !== 'none') {
                        $method = explode('|', $method);
                        foreach ($method as $item) {
                            $item = explode(':', $item);
                            $params[$item[0]] = $item[1];
                        }
                        $parms['method'] = $params;
                    }
                    break;
                case (class_exists($class)):
                    $current_obj = $this;
                    if ($class !== $this->className) {
                        $current_obj = new $class();
                    }

                    if (!method_exists($current_obj, $method)) {
                        throw new BadMethodCallException("Méthode " . $method . " inconue dans l'objet " . get_class($current_obj));
                    }
                    break;
                default:
                    throw new InvalidArgumentException("Paramètrage d'évènement mal construit");
            }
            $parms['stopEvent'] = ($parms['stopEvent']) ? 'OUI' : 'NON';

            return $parms;
        }
        throw new InvalidArgumentException("Tableau Event incompatible");
    }

    /**
     * @param string $class
     * @param string $method
     * @param bool $stopEvent
     * @return array
     */
    public static function formatEvent(string $class, string $method, bool $stopEvent): array
    {
        return [
            'class' => (string)$class,
            'method' => (string)$method,
            'stopEvent' => (bool)$stopEvent
        ];
    }

    /** **************************************************************************************************
     * méthodes privées de la classe                                                                     *
     * *************************************************************************************************** */

    /**
     * @return array
     * @throws ReflectionException
     */
    public static function getConstants(): array
    {
        return (new ReflectionClass(static::class))->getConstants();
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getDisplayConstants(): array
    {
        $retour = [];
        if (empty($this->constants)) {
            $this->constants = self::getConstants();
        }
        if (empty(self::$const_display)) {
            foreach ($this->constants as $key => $constant) {
                $pos = strpos($key, 'DISPLAY');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_display = $retour;
        } else {
            $retour = self::$const_display;
        }

        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getEventConstants(): array
    {
        $retour = [];
        if (empty(self::$const_event)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'EVENT');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_event = $retour;
        } else {
            $retour = self::$const_event;
        }
        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getCssColorConstants(): array
    {
        $retour = [];
        if (empty(self::$const_css_color)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'CSS_COLOR');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_css_color = $retour;
        } else {
            $retour = self::$const_css_color;
        }
        return $retour;
    }

    public function validate_css_color(string $color)
    {
        return (in_array($color, $this->getCssColorConstants())) ? $color : false;
    }
}