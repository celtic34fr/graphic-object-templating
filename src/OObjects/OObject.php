<?php


namespace GraphicObjectTemplating\OObjects;


use Exception;
use GraphicObjectTemplating\OObjects\ODContained\ODButton;
use ReflectionClass;
use ReflectionException;
use RuntimeException;

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
 */

/** TODO : revoir la mise en oeuvre et gestion des infoBulle qque soit l'objet GOT */

class OObject
{
    public $id;
    public $properties;

    const DISPLAY_NONE    = 'none';
    const DISPLAY_BLOCK   = 'block';
    const DISPLAY_INLINE  = 'inline';
    const DISPLAY_INBLOCK = 'inline-block';
    const NO_DISPLAY      = 'noDisplay';

    const BOOLEAN_TRUE    = 'true';
    const BOOLEAN_FALSE   = 'false';

    protected static array $const_display;
    protected static array $const_event;


    /**
     * OObject constructor.
     * @param string $id
     * @param array $properties
     */
    public function __construct(string $id, array $properties)
    {
        $this->properties = $this->constructor($id, $properties);
    }

    /**
     * @param string $id
     * @param array $properties
     * @return array
     */
    public function constructor(string $id, array $properties) : array
    {
        $path = __DIR__. '/../../params/oobjects/oobject.config.php';
        $this->id = $id;
        $oobj_properties = require $path;
        foreach ($oobj_properties as $key => $val) {
            if (!array_key_exists($key, $properties))
                $properties[$key] = $val;
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
        return  false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key) : bool
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
//            case 'inforBulle':
//                switch (true){
//                    case (is_array($val)) :
//    					$val = new OTInfoBulle($val);
//    					break;
//                    case !($val instanceOf OTInfoBulle) :
//    					throw new RuntimeException("Attribut OTInfoBulle valeur fournie incorrecte");
//				}
//                break;
            case 'event':
                throw new Exception("Affectation directe d'évènement impossible, passer par méthodes adaptées");
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
    /** TODO tester l'insertion d'offset */
    /** TODO voir l'ajout des class CSS col-chiffre et offset-chiffre */
    protected function validate_widthBT($val)
    {
        if (!empty($val)) {
            $ret = [];
            $lxs = $lsm = $lmd = $llg = 0;
            $ixs = $ism = $imd = $ilg = 0;

            switch (true) {
                case (is_numeric($val) and (int)$val <= 12):
                    $lxs = $lsm = $lmd = $llg = (int) $val;
                    $ixs = $ism = $imd = $ilg = 12 - (int) $val;
                    break;
                default:
                    foreach (explode(':', $val) as $item) {
                        $key = strtoupper($item);
                        switch (substr($key, 0, 2)) {
                            case 'WL':
                                $llg = (int) substr($key, 2);
                                break;
                            case 'WM':
                                $lmd = (int) substr($key, 2);
                                break;
                            case 'WS':
                                $lsm = (int) substr($key, 2);
                                break;
                            case 'WX':
                                $lxs = (int) substr($key, 2);
                                break;
                            case 'OL':
                                $ilg = (int) substr($key, 2);
                                break;
                            case 'OM':
                                $imd = (int) substr($key, 2);
                                break;
                            case 'OS':
                                $ism = (int) substr($key, 2);
                                break;
                            case 'OX':
                                $ixs = (int) substr($key, 2);
                                break;
                            default:
                                switch ($key[0]) {
                                    case 'W':
                                        $llg = (int) substr($key, 1);
                                        $lmd = $lsm = $lxs = $llg;
                                        break;
                                    case 'O':
                                        $ilg = (int) substr($key, 1);
                                        $imd = $ism = $ixs = $ilg;
                                        break;
                                }
                                break;
                        }
                    }
                    break;
            }

            if ($llg) $ret['WL'] = 'WL'.$llg; if ($ilg) $ret['OL'] = 'OL'.$ilg;
            if ($lmd) $ret['WM'] = 'WM'.$lmd; if ($imd) $ret['OM'] = 'OM'.$imd;
            if ($lsm) $ret['WS'] = 'WS'.$lsm; if ($ism) $ret['OS'] = 'OS'.$ism;
            if ($lxs) $ret['WX'] = 'WX'.$lxs; if ($ixs) $ret['OX'] = 'OX'.$ixs;

            if (count($ret) > 0) return implode(':', $ret);
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
    public function merge_properties(array $add_properties, array $properties) : array
    {
        foreach ($add_properties as $key=>$val) {
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
    public function setEvent(string $key, array $parms) : bool
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
        throw new RuntimeException("Evènement ".$key." tableau paramètres incorrects");
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
    protected function validate_event_parms(array $parms) : array
    {
        $valid = array_key_exists('class', $parms) && $parms['class'];
        $valid = $valid && array_key_exists('method', $parms) && $parms['method'];
        $valid = $valid && array_key_exists('stopEvent', $parms) && is_bool($parms['stopEvent']);

        if ($valid) {
            $class       = $parms['class'];
            $method      = $parms['method'];

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
                    if ($class !== $this->className) $current_obj = new $class();

                    if (!method_exists($current_obj, $method)) {
                        throw new Exception("Méthode ".$method." inconue dans l'objet ".get_class($current_obj));
                    }
                    break;
                default:
                    throw new Exception("Paramètrage d'évènement mal construit");
            }
            $parms['stopEvent'] = ($parms['stopEvent']) ? 'OUI' : 'NON';

            return $parms;
        }
        throw new RuntimeException("Tableau Event incompatible");
    }

    /**
     * @param string $class
     * @param string $method
     * @param bool $stopEvent
     * @return array
     */
    public static function formatEvent(string $class, string $method, bool $stopEvent) : array
    {
        return [
            'class' => (string) $class,
            'method' => (string) $method,
            'stopEvent' => (bool) $stopEvent
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
        if (empty($this->constants)) { $this->constants = self::getConstants(); }
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
    private function getEventConstants() : array
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
}