<?php


namespace GraphicObjectTemplating\OObjects\ODContained;


use GraphicObjectTemplating\OObjects\ODContained;

class ODCheckbox extends ODContained
{
    const CHECKFORME_HORIZONTAL = 'horizontal';
    const CHECKFORME_VERTICAL = 'vertical';

    const CHECKPLACEMENT_LEFT  = "left";
    const CHECKPLACEMENT_RIGHT = "right";

    const CHECKTYPE_CHECKBOX    = "checkbos";
    const CHECKTYPE_SWITCH      = "switch";

    /**
     * @var mixed|void|null
     */
    private static $const_checktype;
    /**
     * @var mixed|void|null
     */
    private static $const_checkforme;
    /**
     * @var mixed|void|null
     */
    private static $const_checkplacement;

    /**
     * ODInput constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__. '/../../../params/oobjects/odcontained/odcheckbox/odcheckbox.config.php';
        $properties = require $path;
        parent::__construct($id, $properties);

        $properties = $this->constructor($id, $properties);
        $this->properties = $properties;

        if ((int)$this->widthBT ===0 ) $this->widthBT = 12;
    }

    /**
     * @param string $id
     * @param array $properties
     * @return array
     */
    public function constructor($id, $properties) : array
    {
        $properties = parent::constructor($id, $properties);

        $typeObj = $properties['typeObj'];
        $object = $properties['object'];
        $template = $properties['template'];
        list($properties['template'], $properties['className']) = $this->set_Termplate_ClassName($typeObj, $object, $template);

        return $properties;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key) : bool
    {
        return parent::__isset($key);
    }

    /**
     * @param string $key
     * @return mixed|void|null
     */
    public function __get(string $key)
    {
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
                $val = $this->validate_checktype($val);
                break;
            case 'label':
                $val = (string) $val;
                break;
            case 'labelWidthBT':
            case 'inputWidthBT':
            case 'checkLabelWidthBT':
            case 'checkInputWidthBT':
                $val = $this->validate_widthBT($val);
                break;
            case 'forme':
                $val = $this->validate_checkforme($val);
                break;
            case 'placement':
                $val = $this->validate_checkplacement($val);
                break;
            default:
                return parent::__set($key, $val);
        }
        $this->properties[$key] = $val;
        return true;
    }

    private function validate_checktype($val)
    {
        return in_array($val, $this->getCheckTypeConstants(), true) ? $val : self::CHECKTYPE_CHECKBOX;
    }

    private function getCheckTypeConstants()
    {
        $retour = [];
        if (empty(self::$const_checktype)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'CHECKTYPE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_checktype = $retour;
        } else {
            $retour = self::$const_checktype;
        }
        return $retour;
    }

    public function enaDispBySide()
    {
        $this->labelWidthBT = null;
        $this->inputWidthBT = null;
    }

    public function enaDispUnder()
    {
        $this->labelWidthBT = 12;
        $this->inputWidthBT = 12;
    }

    public function enaCheckBySide()
    {
        $this->checkLabelWidthBT = null;
        $this->checkInputWidthBT = null;
    }

    public function enaCheckUnder()
    {
        $this->checkLabelWidthBT = 12;
        $this->checkInputWidthBT = 12;
    }

    private function validate_checkforme($val)
    {
        return in_array($val, $this->getCheckFormeConstants(), true) ? $val : self::CHECKFORME_HORIZONTAL;
    }

    private function getCheckFormeConstants()
    {
        $retour = [];
        if (empty(self::$const_checkforme)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'CHECKFORME');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_checkforme = $retour;
        } else {
            $retour = self::$const_checkforme;
        }
        return $retour;
    }

    private function validate_checkplacement($val)
    {
        return in_array($val, $this->getCheckPlacementConstants(), true) ? $val : self::CHECKPLACEMENT_LEFT;
    }

    private function getCheckPlacementConstants()
    {
        $retour = [];
        if (empty(self::$const_checkplacement)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'CHECKPLACEMENT');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_checkplacement = $retour;
        } else {
            $retour = self::$const_checkplacement;
        }
        return $retour;
    }

}