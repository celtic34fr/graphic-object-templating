<?php


namespace GraphicObjectTemplating\OObjects\ODContained;


use Exception;
use GraphicObjectTemplating\OObjects\ODContained;
use ReflectionException;

class ODCheckbox extends ODContained
{
    const CHECKFORME_HORIZONTAL = 'horizontal';
    const CHECKFORME_VERTICAL = 'vertical';

    const CHECKPLACEMENT_LEFT = "left";
    const CHECKPLACEMENT_RIGHT = "right";

    const CHECKTYPE_CHECKBOX = "checkbox";
    const CHECKTYPE_SWITCH = "switch";

    const CHECKNATURE_DEFAULT = "checkbox";
    const CHECKNATURE_PRIMARY = "checkbox checkbox-primary";
    const CHECKNATURE_SUCCESS = "checkbox checkbox-success";
    const CHECKNATURE_INFO = "checkbox checkbox-info";
    const CHECKNATURE_WARNING = "checkbox checkbox-warning";
    const CHECKNATURE_DANGER = "checkbox checkbox-danger";

    const CHECKBOX_CHECK   = "check";
    const CHECKBOX_UNCHECK = "uncheck";

    const CHECKSTATE_ENABLE = 'enable';
    const CHECKSTATE_DISABLE = 'disable';

    const CHECKCHECKBOX = [
        'type', 'value', 'libel', 'placement', 'nature', 'state'
    ];
    const CHECKSWITCH = [
        'type', 'value', 'libelYes', 'libelNo', 'natureYes', 'backgrYes', 'natureNo', 'backgrNo', 'state'
    ];

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
     * @var mixed|void|null
     */
    private static $const_checknature;

    /**
     * ODInput constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__ . '/../../../params/oobjects/odcontained/odcheckbox/odcheckbox.config.php';
        $properties = require $path;
        parent::__construct($id, $properties);

        $properties = $this->constructor($id, $properties);
        $this->properties = $properties;

        if ((int)$this->widthBT === 0) $this->widthBT = 12;
    }

    /**
     * @param string $id
     * @param array $properties
     * @return array
     */
    public function constructor($id, $properties): array
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
    public function __isset(string $key): bool
    {
        return parent::__isset($key);
    }

    /**
     * @param string $key
     * @return mixed|void|null
     */
    public function __get(string $key)
    {
        switch ($key) {
            case 'option':
                throw new Exception("l'attribut optioon inaccessible, veuillez utilise les méthode spécidfique");
            default:
                return parent::__get($key);
        }
    }

    /**
     * @param string $key
     * @param $val
     * @return mixed|void|null
     */
    public function __set(string $key, $val)
    {
        switch ($key) {
//            case 'type':
//                $val = $this->validate_checktype($val);
//                break;
            case 'label':
                $val = (string)$val;
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
            case 'options':
                $val = $this->validate_optionsArray($val);
                break;
            default:
                return parent::__set($key, $val);
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @param $val
     * @return mixed|string
     */
    private function validate_checktype($val)
    {
        return in_array($val, $this->getCheckTypeConstants(), true) ? $val : self::CHECKTYPE_CHECKBOX;
    }

    /**
     * @return array|mixed|void
     * @throws ReflectionException
     */
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

    /**
     * @return array|mixed|void
     * @throws ReflectionException
     */
    private function getCheckNatureConstants()
    {
        $retour = [];
        if (empty(self::$const_checknature)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'CHECKNATURE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_checknature = $retour;
        } else {
            $retour = self::$const_checknature;
        }
        return $retour;
    }

    /**
     * set widthBT of ODCheckbox for label + input on same line
     */
    public function enaDispBySide()
    {
        $this->labelWidthBT = null;
        $this->inputWidthBT = null;
    }

    /**
     * set widthBT of ODCheckbox for input under label
     */
    public function enaDispUnder()
    {
        $this->labelWidthBT = 12;
        $this->inputWidthBT = 12;
    }

    /**
     * set widthBT checkbox for label and input on same line
     */
    public function enaCheckBySide()
    {
        $this->checkLabelWidthBT = null;
        $this->checkInputWidthBT = null;
    }

    /**
     * set widthBT checkbox for input under label
     */
    public function enaCheckUnder()
    {
        $this->checkLabelWidthBT = 12;
        $this->checkInputWidthBT = 12;
    }

    /**
     * @param $val
     * @return mixed|string
     */
    private function validate_checkforme($val)
    {
        return in_array($val, $this->getCheckFormeConstants(), true) ? $val : self::CHECKFORME_HORIZONTAL;
    }

    /**
     * @return array|mixed|void
     * @throws ReflectionException
     */
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

    /**
     * @param $val
     * @return mixed|string
     */
    private function validate_checkplacement($val)
    {
        return in_array($val, $this->getCheckPlacementConstants(), true) ? $val : self::CHECKPLACEMENT_LEFT;
    }

    /**
     * @return array|mixed|void
     * @throws ReflectionException
     */
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

    /**
     * @param $value
     * @param array $options
     * @throws Exception
     */
    public function addOption($value, array $options)
    {
        $options = $this->validate_optionArray($options);
        $item = [];
        switch ($options['type']) {
            case self::CHECKTYPE_CHECKBOX:
                $item['libel']      = $options['libel'];
                $item['nature']     = $options['nature'];
                $item['value']      = $value;
                break;
            case self::CHECKTYPE_SWITCH:
                $item['libelYes']   = $options['libelYes'];
                $item['natureYes']  = $options['natureYes'];
                $item['backgrYes']  = $options['backgrYes'];
                $item['libelNo']    = $options['libelNo'];
                $item['natureNo']   = $options['natureNo'];
                $item['backgrNo']   = $options['backgrNo'];
                break;
        }
        $item['check']                      = self::CHECKBOX_UNCHECK;
        $item['state']                      = $options['state'];
        if (array_key_exists($options['value'], $this->options)) {
            throw new Exception("Tableau des options : clé ".$options['value']
                                                                            ." déjà présente, insertion impossible");
        }
        $this->options[$options['value']]   = $item;
    }

    /**
     * @param $value
     * @throws Exception
     */
    public function rmOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value) {
            if (!array_key_exists($value, $this->options)) {
                throw new Exception(" tableau des options : clé $value inconnue");
            }
            unset($this->options[$value]);
        }
    }

    /**
     * @param $value
     * @param array $options
     * @throws Exception
     */
    public function setOption($value, array $options)
    {
        $options = $this->validate_optionArray($options);
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value) {
            if (!array_key_exists($value, $this->options)) {
                throw new Exception(" tableau des options : clé $value inconnue");
            }
            $this->options[$value] = $options;
        }
    }

    /**
     * @param $value
     * @return mixed
     * @throws Exception
     */
    public function getOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value) {
            if (!array_key_exists($value, $this->options)) {
                throw new Exception(" tableau des options : clé $value inconnue");
            }
            return $this->options[$value];
        }
        throw new Exception("tableau options : clé d'accès incorrecte");
    }

    /**
     * @param array $options
     * @return array $options
     *
     * options array checkbox :
     * -> type = self::CHECKTYPE_CHECKBOX,
     * -> value,    : value to return if checkbox is checked
     * -> libel,    : text to present of side of checkbox
     * -> placement : side of checkbox to present 'libel' (left or right)
     * -> nature    : CSS classes to code aspect of checkbox
     * -> state     : enable / disable ckeckbox option
     *
     * options array switch
     * -> type = self::CHECKTYPE_CHECKBOX,
     * -> value,    : value to return if checkbox is checked
     * -> libelYes  : text to present on left side
     * -> linelNo   : text to present on right side
     * -> natureYes : Css for coding button color for Yes
     * -> backgrYes : Css for coding background color for Yes
     * -> natureNo  : Css for coding button color for No
     * -> backgrNo  : Css for coding background color for No
     * -> state     : enable / disable ckeckbox option
     */
    private function validate_optionArray(array $option)
    {
        $type = array_key_exists('type', $option) ? $option['type'] : null;
        $type = $this->validate_checktype($type);
        $nbFields = [];
        if ($type) {
            $maxCount =
                ($type == self::CHECKTYPE_CHECKBOX) ? count(self::CHECKCHECKBOX) : count(self::CHECKSWITCH);
            if (!array_key_exists($type, $nbFields)) $nbFields[$type] = 0;
            foreach ($option as $key => $params) {
                switch ($key) {
                    case 'type':
                    case 'value':
                        break;
                    case 'libel':
                    case 'libelYes':
                    case 'libelNo':
                    case 'natureYes':
                    case 'natureNo':
                        $params = (string)$params;
                        break;
                    case 'placement':
                        $params = $this->validate_checkplacement($params);
                        break;
                    case 'nature':
                        $params = $this->validate_checknature($params);
                        break;
                    case 'state':
                        $params = (bool)$params;
                        break;
                    default:
                        throw new Exception("Paramètre $key incohérent dans un tableau de paramètres d'option");
                }
                $option[$key] = $params;
                $nbFields[$type] += 1;
            }
        }

        if (!array_key_exists(self::CHECKTYPE_CHECKBOX, $nbFields)
            && !array_key_exists(self::CHECKTYPE_SWITCH, $nbFields)) {
            throw new Exception("tableau de paramètre d'option non typé, veuillez corriger");
        }
        if ($nbFields[$type] != $maxCount) {
            throw new Exception("tableau paramètre d'option type $type incorrect, attendu $maxCount, trouvé "
                .(int)$nbFields[$type] );
        }

        return $option;
    }

    /**
     * @param array $options
     * @return array
     * @throws Exception
     */
    private function validate_optionsArray(array $options)
    {
        foreach ($options as $idx => $option) {
            $options[$idx] = $this->validate_optionArray($option);
        }
        return $options;
    }

    /**
     * @param $val
     * @return mixed|string
     * @throws ReflectionException
     */
    private function validate_checknature($val)
    {
        return in_array($val, $this->getCheckNatureConstants(), true) ? $val : self::CHECKNATURE_DEFAULT;
    }

    /**
     * @param $value
     */
    public function checkOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value) {
            if (array_key_exists($value, $this->options)) {
                $this->options[$value]['check'] = self::CHECKBOX_CHECK;
            }
        }
    }

    /**
     * @param $value
     */
    public function uncheckOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value) {
            if (array_key_exists($value, $this->options)) {
                $this->options[$value]['check'] = self::CHECKBOX_UNCHECK;
            }
        }
    }

    /**
     *
     */
    public function checkAllOption()
    {
        foreach ($this->options as $value => $option) {
            $this->options[$value]['check'] = self::CHECKBOX_CHECK;
        }
    }

    /**
     *
     */
    public function uncheckAllOption()
    {
        foreach ($this->options as $value => $option) {
            $this->options[$value]['check'] = self::CHECKBOX_UNCHECK;
        }
    }

    /**
     * @return array
     */
    public function getCheckedOption()
    {
        $checked = [];
        foreach ($this->options as $value => $option) {
            if ($option['check'] === self::CHECKBOX_CHECK) {
                $checked[] = $value;
            }
        }
        return $checked;
    }

    /**
     * @param $value
     */
    public function enaOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value) {
            if (array_key_exists($value, $this->options)) {
                $this->options[$value]['state'] = true;
            }
        }
    }

    /**
     * @param $value
     */
    public function disOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value) {
            if (array_key_exists($value, $this->options)) {
                $this->options[$value]['state'] = false;
            }
        }
    }

    /**
     * @param $value
     * @return string
     * @throws Exception
     */
    public function getStateOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value) {
            if (array_key_exists($value, $this->options)) {
                return $this->options[$value]['state'] ? self::CHECKSTATE_ENABLE : self::CHECKSTATE_DISABLE;
            }
        }
        throw new Exception("Paramètre demande état option incorrect");
    }

    /**
     *
     */
    public function enaAllOptions()
    {
        foreach ($this->options as $value => $option) {
            $this->options[$value]['state'] = true;
        }
    }

    /**
     *
     */
    public function disAllOptions()
    {
        foreach ($this->options as $value => $option) {
            $this->options[$value]['state'] = false;
        }
    }

    /**
     * @return array
     */
    public function getStateOptions()
    {
        $states = [];
        foreach ($this->options as $value => $option) {
                $states[] = $value ? self::CHECKSTATE_ENABLE : self::CHECKSTATE_DISABLE;
        }
        return $states;
    }
}