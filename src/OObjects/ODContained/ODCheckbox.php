<?php


namespace GraphicObjectTemplating\OObjects\ODContained;


use BadFunctionCallException;
use Exception;
use GraphicObjectTemplating\OObjects\ODContained;
use InvalidArgumentException;
use LogicException;
use ReflectionException;
use UnexpectedValueException;

/**
 * Class ODCheckbox
 * @package GraphicObjectTemplating\OObjects\ODContained
 *
 * méthodes
 * --------
 * __construct(string $id)
 * __get(string $key)
 * __set(string $key, $val)
 * enaDispBySide()
 * enaDispUnder()
 * enaCheckBySide()
 * enaCheckUnder()
 * addOption($value, array $options)
 * rmOption($value)
 * setOption($value, array $options)
 * getOption($value)
 * validate_optionArray(array $option)
 * validate_optionsArray(array $options)
 * checkOption($value)
 * uncheckOption($value)
 * checkAllOptions()
 * uncheckAllOptions()
 * getCheckedOptions()
 * enaOption($value)
 * disOption($value)
 * getStateOption($value)
 * enaAllOptions()
 * disAllOptions()
 * getStateOptions()
 */
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

    const CHECKBOX_CHECK = "check";
    const CHECKBOX_UNCHECK = "uncheck";

    const CHECKSTATE_ENABLE = 'enable';
    const CHECKSTATE_DISABLE = 'disable';

    const CHECKCHECKBOX = [
        'type', 'value', 'libel', 'placement', 'nature', 'state'
    ];
    const CHECKSWITCH = [
        'type', 'value', 'libelYes', 'libelNo', 'natureYes', 'backgrYes', 'natureNo', 'backgrNo', 'state'
    ];

    const ERR_UNEXPECTED_VALUE_MSG = "Unexpected value";

    /**
     * ODInput constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__ . '/../../../params/oobjects/odcontained/odcheckbox/odcheckbox.config.php';
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
        if ($key === 'option') {
            throw new BadFunctionCallException("l'attribut option inaccessible, veuillez utilise les méthode spécidfique");
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
        switch ($key) {
//            case 'type':
//                $val = $this->validate_By_Constants($val, "CHECKTYPE_", self::CHECKTYPE_CHECKBOX);
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
                $val = $this->validate_By_Constants($val, "CHECKFORME_", self::CHECKFORME_HORIZONTAL);
                break;
            case 'placement':
                $val = $this->validate_By_Constants($val, "CHECKPLACEMENT_", self::CHECKPLACEMENT_LEFT);
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
                $item['libel'] = $options['libel'];
                $item['nature'] = $options['nature'];
                $item['value'] = $value;
                break;
            case self::CHECKTYPE_SWITCH:
                $item['libelYes'] = $options['libelYes'];
                $item['natureYes'] = $options['natureYes'];
                $item['backgrYes'] = $options['backgrYes'];
                $item['libelNo'] = $options['libelNo'];
                $item['natureNo'] = $options['natureNo'];
                $item['backgrNo'] = $options['backgrNo'];
                break;
            default:
                throw new UnexpectedValueException(self::ERR_UNEXPECTED_VALUE_MSG);
        }
        $item['check'] = self::CHECKBOX_UNCHECK;
        $item['state'] = $options['state'];
        if (array_key_exists($options['value'], $this->options)) {
            throw new LogicException("Tableau des options : clé " . $options['value']
                . " déjà présente, insertion impossible");
        }
        $this->options[$options['value']] = $item;
    }

    /**
     * @param $value
     * @throws Exception
     */
    public function rmOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value) {
            if (!array_key_exists($value, $this->options)) {
                throw new InvalidArgumentException(" tableau des options : clé $value inconnue");
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
                throw new InvalidArgumentException(" tableau des options : clé $value inconnue");
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
                throw new InvalidArgumentException(" tableau des options : clé $value inconnue");
            }
            return $this->options[$value];
        }
        throw new InvalidArgumentException("tableau options : clé d'accès incorrecte");
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
        $type = $this->validate_By_Constants($type, "CHECKTYPE_", self::CHECKTYPE_CHECKBOX);
        $nbFields = [];
        if ($type) {
            $maxCount =
                ($type == self::CHECKTYPE_CHECKBOX) ? count(self::CHECKCHECKBOX) : count(self::CHECKSWITCH);
            if (!array_key_exists($type, $nbFields)) {
                $nbFields[$type] = 0;
            }
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
                        $params = $this->validate_By_Constants($params, "CHECKPLACEMENT_", self::CHECKPLACEMENT_LEFT);
                        break;
                    case 'nature':
                        $params = $this->validate_By_Constants($params, "CHECKNATURE_", self::CHECKNATURE_DEFAULT);
                        break;
                    case 'state':
                        $params = (bool)$params;
                        break;
                    default:
                        throw new UnexpectedValueException("Paramètre $key incohérent dans un tableau de paramètres d'option");
                }
                $option[$key] = $params;
                $nbFields[$type] += 1;
            }
        }

        if (!array_key_exists(self::CHECKTYPE_CHECKBOX, $nbFields)
            && !array_key_exists(self::CHECKTYPE_SWITCH, $nbFields)) {
            throw new InvalidArgumentException("tableau de paramètre d'option non typé, veuillez corriger");
        }
        if ($nbFields[$type] != $maxCount) {
            throw new InvalidArgumentException("tableau paramètre d'option type $type incorrect, attendu $maxCount, trouvé "
                . (int)$nbFields[$type]);
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
     * @param $value
     */
    public function checkOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value && array_key_exists($value, $this->options)) {
            $this->options[$value]['check'] = self::CHECKBOX_CHECK;
        }
    }

    /**
     * @param $value
     */
    public function uncheckOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value && array_key_exists($value, $this->options)) {
            $this->options[$value]['check'] = self::CHECKBOX_UNCHECK;
        }
    }

    /**
     * @return bool
     */
    public function checkAllOptions()
    {
        foreach ($this->options as $value => $option) {
            $this->options[$value]['check'] = self::CHECKBOX_CHECK;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function uncheckAllOptions()
    {
        foreach ($this->options as $value => $option) {
            $this->options[$value]['check'] = self::CHECKBOX_UNCHECK;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getCheckedOptions()
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
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value && array_key_exists($value, $this->options)) {
            $this->options[$value]['state'] = true;
        }
    }

    /**
     * @param $value
     */
    public function disOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value && array_key_exists($value, $this->options)) {
            $this->options[$value]['state'] = false;
        }
    }

    /**
     * @param $value
     * @return string
     * @throws Exception
     */
    public function getStateOption($value)
    {
        if (!is_bool($value) && !is_array($value) && !is_object($value) && $value && array_key_exists($value, $this->options)) {
            return $this->options[$value]['state'] ? self::CHECKSTATE_ENABLE : self::CHECKSTATE_DISABLE;
        }
        throw new InvalidArgumentException("Paramètre demande état option incorrect");
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