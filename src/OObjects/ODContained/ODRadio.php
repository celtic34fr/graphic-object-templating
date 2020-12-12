<?php


namespace GraphicObjectTemplating\OObjects\ODContained;


use Exception;
use GraphicObjectTemplating\OObjects\ODContained;
use ReflectionException;

/**
 * Class ODRadio
 * @package GraphicObjectTemplating\OObjects\ODContained
 *
 * attributs
 * ---------
 * label
 * options
 * forme
 * hMargin
 * vMargin
 * place
 * event
 * labelWidthBT
 * inputWidthBT
 * checkLabelWidthBT
 * checkInputWidthBT
 * placement
 *
 * méthodes
 * --------
 * __construct($id)         constructeur de l'objet, obligation de fournir $id identifiant de l'objet
 * constructor($id, $properties): array
 * __isset(string $key): bool
 * __get(string $key)
 * __set(string $key, $val)
 * addOption(string $value, array $options)
 * rmOption(string $value)
 * getOption(string $value)
 * getStateConstants() : array
 * setOption(string $value, array $options)
 * checkOption(string $value)
 * uncheckOption(string $value)
 * enaOption(string $value)
 * disOption(string $value)
 * enaDispBySide(): ODRadio
 * enaDispUnder() : ODRadio
 * getCheckConstants() : array
 * getTypeConstants() : array
 * validate_option(array $options) :array
 * validate_options(array $val) :array
 */
class ODRadio extends ODContained
{
    const RADIOSTATE_ENABLE   = true;
    const RADIOSTATE_DISABLE  = false;

    const RADIOPLACEMENT_LEFT  = "left";
    const RADIOPLACEMENT_RIGHT = "right";

    const RADIOCHECK_CHECK    = "check";
    const RADIOCHECK_UNCHECK  = "uncheck";

    const RADIOTYPE_DEFAULT = "radio";
    const RADIOTYPE_PRIMARY = "radio radio-primary";
    const RADIOTYPE_SUCCESS = "radio radio-success";
    const RADIOTYPE_INFO    = "radio radio-info";
    const RADIOTYPE_WARNING = "radio radio-warning";
    const RADIOTYPE_DANGER  = "radio radio-danger";

    const RADIOFORM_HORIZONTAL  = 'radio-horizontal';
    const RADIOFORM_VERTICAL    = 'radio-vertical';

    protected static array  $const_state;
    protected static array  $const_check;
    protected static array  $const_type;


    /**
     * ODTable constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__ . '/../../../params/oobjects/odcontained/odradio/odradio.config.php';
        $properties = require $path;
        parent::__construct($id, $properties);

        $properties = $this->constructor($id, $properties);
        if ((int)$properties['widthBT'] === 0) {
            $properties['widthBT'] = $this->validate_widthBT(12);
        }
        $this->properties = $properties;
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
        if ($key == 'option') {
            throw new Exception("l'attribut option inaccessible, veuillez utilise les méthode spécidfique");
        } else {
            return parent::__isset($key);
        }
    }

    /**
     * @param string $key
     * @return false|mixed|null
     * @throws Exception
     */
    public function __get(string $key)
    {
        switch ($key) {
            case 'option':
                throw new Exception("l'attribut option inaccessible, veuillez utilise les méthode spécidfique");
            case 'checked':
            case 'value':
                $checked = [];
                foreach ($this->options as $value => $option) {
                    if ($option['check'] === self::RADIOCHECK_CHECK) {
                        $checked[] = $value;
                    }
                }
                return $checked;
            case 'states':
                $states = [];
                foreach ($this->options as $value => $option) {
                    if ($option['check'] === self::RADIOCHECK_CHECK) {
                        $states[$value] = $option['state'] ? 'enable' : 'disable';
                    }
                }
                return $states;
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
            case 'label':
                $val = (string)$val;
                break;
            case 'labelWidthBT':
            case 'selectWidthBT':
                $val = $this->validate_widthBT($val);
                break;
            case 'option':
                throw new Exception("l'attribut option inaccessible, veuillez utilise les méthode spécidfique");
                break;
            case 'options':
                $val = $this->validate_options($val);
                break;
            case 'checkAllAs':
                $val = (bool)$val;
                foreach ($this->options as $value => $option) {
                    $this->options[$value]['check'] = ($val) ? self::RADIOCHECK_CHECK : self::RADIOCHECK_UNCHECK;
                }
                break;
            case 'ableAllAs':
                $val = (bool)$val;
                foreach ($this->options as $value => $option) {
                    $this->options[$value]['check'] = ($val) ? self::RADIOSTATE_ENABLE : self::RADIOSTATE_DISABLE;
                }
                break;
            default:
                return parent::__set($key, $val);
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @param string $value
     * @param array $options
     * @throws ReflectionException
     */
    public function addOption(string $value, array $options)
    {
        if (!array_key_exists($value, $this->options)) {
            $options['value'] = $value;
            $options = $this->validate_option($options);
            $this->options[$value] = $options;
            return $this;
        }
        return false;
    }

    /**
     * @param string $value
     * @return ODRadio|bool
     * @throws Exception
     */
    public function rmOption(string $value)
    {
        if (!empty($value) && array_key_exists($value, $this->options)) {
            unset($this->options[$value]);
            return $this;
        }
        return false;
    }

    /**
     * @param string $value
     * @return bool
     */
    public function getOption(string $value)
    {
        if (!empty($value) && array_key_exists($value, $this->options)) {
            return $this->options[$value];
        }
        return false;
    }

    /**
     * @param string $value
     * @param array $options
     * @return $this|false
     * @throws ReflectionException
     */
    public function setOption(string $value, array $options)
    {
        if (array_key_exists($value, $this->options)) {
            $options['value'] = $value;
            $options = $this->validate_option($options);
            $this->options[$value] = $options;
            return $this;
        }
        return false;
    }

    /**
     * @param string $value
     * @return ODRadio|bool
     */
    public function checkOption(string $value): bool
    {
        if (array_key_exists($value, $this->options)) {
            $this->options[$value]['check'] = self::RADIOCHECK_CHECK;
            return $this;
        }
        return false;
    }

    /**
     * @param string $value
     * @return ODRadio|bool
     */
    public function uncheckOption(string $value)
    {
        if (array_key_exists($value, $this->options)) {
            $this->options[$value]['check'] = self::RADIOCHECK_UNCHECK;
            return $this;
        }
        return false;
    }

    /**
     * @param string $value
     * @return ODRadio|bool
     * @throws Exception
     */
    public function enaOption(string $value)
    {
        if (!empty($value) && array_key_exists($value, $this->options)) {
            $this->options[$value]['state'] = self::RADIOSTATE_ENABLE;
            return $this;
        }
        return false;
    }

    /**
     * @param string $value
     * @return ODRadio|bool
     * @throws Exception
     */
    public function disOption(string $value)
    {
        if (!empty($value) && array_key_exists($value, $this->options)) {
            $this->options[$value]['state'] = self::RADIOSTATE_DISABLE;
            return $this;
        }
        return false;
    }

    /**
     * @return ODRadio
     * @throws Exception
     */
    public function enaDispBySide(): ODRadio
    {
        $this->properties['labelWidthBT'] = '';
        $this->properties['selectWidthBT'] = '';

        return $this;
    }

    /**
     * @return ODRadio
     * @throws Exception
     */
    public function enaDispUnder() : ODRadio
    {
        $widthLabChkBT  = $this->validate_widthBT(12);
        $this->properties['labelWidthBT'] = $widthLabChkBT;
        $this->properties['selectWidthBT'] = $widthLabChkBT;

        return $this;
    }



    /**
     * @return array
     * @throws ReflectionException
     */
    public function getStateConstants() : array
    {
        $retour = [];
        if (empty(self::$const_state)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'RADIOSTATE_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_state = $retour;
        } else {
            $retour = $this->const_state;
        }

        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    public function getCheckConstants() : array
    {
        $retour = [];
        if (empty(self::$const_check)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'RADIOCHECK_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_check = $retour;
        } else {
            $retour = $this->const_check;
        }

        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getTypeConstants() : array
    {
        $retour = [];
        if (empty(self::$const_type)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'RADIOTYPE');
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
     * @param array $options
     * @return array
     * @throws ReflectionException
     */
    private function validate_option(array $options) :array
    {
        foreach ($options as $key => $option) {
            switch ($key) {
                case 'state':
                    $constant = $this->getStateConstants();
                    $default = self::RADIOSTATE_ENABLE;
                    break;
                case 'check':
                    $constant = $this->getCheckConstants();
                    $default = self::RADIOCHECK_UNCHECK;
                    break;
                case 'type':
                    $constant = $this->getTypeConstants();
                    $default = self::RADIOTYPE_DEFAULT;
                    break;
                case 'value':
                    $option = (string)$option;
                    break;
                default:
                    throw new Exception("Paramètre d'option incorrect (".$key.")");
            }
            $options[$key] = array($option, $constant) ? $option : $default;
        }
        return $options;
    }

    /**
     * @param array $val
     * @return array
     * @throws ReflectionException
     */
    private function validate_options(array $val) : array
    {
        foreach ($val as $key => $options) {
            if (!array_key_exists('value', $options)) {
                throw new Exception("Tableau de valeurs d'option sans valeur associée");
            }
            $options = $this->validate_option($options);
            $value = $options['value'];
            if (array_key_exists($value, $this->options)) {
                throw new Exception("Valeur clé de Tableau de valeurs d'option dèjà utilisée");
            }
            $val[$key] = $options;
        }
        return $val;
    }
}