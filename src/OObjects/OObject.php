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
 * getConstantsGroup(): array
 * validate_By_Constants($val, string $cle_contants, $default)
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


    protected static array $const_global;
    protected static array $const_display;
    protected static array $const_event;
    protected static array $const_css_color;

    /**
     * OObject constructor.
     * @param string $id
     * @param array $properties
     */
    public function __construct(string $id, array $properties = null)
    {
        // TODO : revoir la mise en oeuvre et gestion des infoBulle qque soit l'objet GOT
        if ($properties) {
            $this->properties = $this->constructor($id, $properties);
        }
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

    public function object_contructor($id, $properties)
    {
        $properties = $this->constructor($id, $properties);
        $typeObj = $properties['typeObj'];
        $object = $properties['object'];
        $template = $properties['template'];
        list($properties['template'], $properties['className']) = $this->set_Termplate_ClassName($typeObj, $object, $template);
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
                $val = $this->validate_By_Constants($val, "DISPLAY_", self::DISPLAY_BLOCK);
                break;
            case 'autoCenter':
            case 'state':
                $val = (bool)$val;
                break;
            case 'inforBulle':
                switch (true) {
                    case (is_array($val)) :
                        $val = new OTInfoBulle($val);
                        break;
                    case !($val instanceof OTInfoBulle) :
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
     * @param $val
     * @return false|string
     */
    protected function validate_widthBT($val)
    {
        // TODO: tester l'insertion d'offset
        // TODO: voir l'ajout des class CSS col-chiffre et offset-chiffre
        if (empty($val)) {
            return false;
        }

        $rlt = [];
        $rlt["WX"] = $rlt["WS"] = $rlt["WM"] = $rlt["WL"] = 0;
        $rlt["OX"] = $rlt["OS"] = $rlt["OM"] = $rlt["OL"] = 0;
        $prefixes = ["WX", "WS", "WM", "WL", "OX", "OS", "OM", "OL"];

        if (is_numeric($val) and (int)$val <= 12) {
            $rlt["WX"] = $rlt["WS"] = $rlt["WM"] = $rlt["WL"] = (int)$val;
        } else {
            foreach (explode(':', $val) as $item) {
                $key = strtoupper($item);
                $prefix = substr($key, 0, 2);
                if (in_array($prefix, $prefixes)) {
                    $rlt[$prefix] = (int)substr($key, 2);
                } elseif (in_array($key[0], ["W", "O"])) {
                    $rlt[$key[0]."L"] = (int)substr($key, 1);
                    $rlt[$key[0]."M"] = $rlt[$key[0]."S"] = $rlt[$key[0]."X"] = $rlt[$key[0]."L"];
                }
            }
        }

        foreach ($rlt as $key=>$value) {
            if (!$value) { unset($rlt[$key]); }
        }

        if (!empty($val)) {
            return implode(':', $rlt);
        }
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
        return (in_array($key, $this->getConstantsGroup('EVVENT_'))) ? $key : false;
    }

    /**
     * @param array $parms
     * @return array
     * @throws Exception
     */
    protected function validate_event_parms(array $parms): array
    {
        $valid = array_key_exists('class', $parms) and array_key_exists('method', $parms) and array_key_exists('stopEvent', $parms) and !empty($parms['class']) and !empty($parms['method']) and is_bool($parms['stopEvent']);

        if (!$valid) {
            throw new InvalidArgumentException("Tableau Event incompatible");
        }
        $class = $parms['class'];
        $method = $parms['method'];

        switch (true) {
            case ($this->object === 'odbutton' and $this->type === ODButton::BUTTONTYPE_LINK):
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
                $current_obj = ($class === $this->className) ? $this : new $class;

                if (!method_exists($current_obj, $method)) {
                    throw new BadMethodCallException("Méthode " . $method . " inconue dans l'objet " . get_class($current_obj));
                }
                break;
            default:
                if (strtolower($class) !== 'javascript:') {
                    throw new InvalidArgumentException("Paramètrage d'évènement mal construit");
                }
        }
        if ($parms['stopEvent']) {
            $parms['stopEvent'] = 'OUI';
        } else {
            $parms['stopEvent'] = 'NON';
        }

        return $parms;
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
        self::$const_global = null;
        if (empty(self::$const_global)) {
            $reflectionClass = new ReflectionClass(static::class);
            if ($reflectionClass && in_array('getConstants', get_class_methods($reflectionClass))) {
                self::$const_global = $reflectionClass::getConstants();
            }
        }
        return self::$const_global;
    }

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
     * This function is to replace PHP's extremely buggy realpath().
     * @param string The original path, can be relative etc.
     * @return string The resolved path, it might not exist.
     */
    public function truepath($path){
        // whether $path is unix or not
        $unipath=strlen($path)==0 || $path[0] !== '/';
        // attempts to detect if path is relative in which case, add cwd
        if(strpos($path,':')===false && $unipath) {
            $path = getcwd() . DIRECTORY_SEPARATOR . $path;
        }
        // resolve path parts (single dot, double dot and double delimiters)
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.' === $part) {
                continue;
            }
            if ('..' === $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        $path=implode(DIRECTORY_SEPARATOR, $absolutes);
        // resolve any symlinks
        if(file_exists($path) && linkinfo($path)>0) {
            $path = readlink($path);
        }

        // put initial separator that could have been lost
        return !$unipath ? '/'.$path : $path;
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