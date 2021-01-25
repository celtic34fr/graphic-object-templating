<?php


namespace GraphicObjectTemplating\OObjects\ODContained;


use BadFunctionCallException;
use Exception;
use GraphicObjectTemplating\OObjects\ODContained;
use InvalidArgumentException;
use ReflectionException;
use UnexpectedValueException;

/**
 * Class ODSelect
 * @package GraphicObjectTemplating\OObjects\ODContained
 *
 * attributs
 * ---------
 * options      : tableau contenant la liste et les valeurs des options de la sélection
 * multiple     : précise si l'on peut selectionner une option (falsz) ou plusieurs (true)
 * label        : texte à afficher avec la sélection pour lui donner un titre, une qualification
 * labelClass   : classe(s) CSS à appliquer sur l'affichage de l'attribut label
 * selectClass  : classe(s) CSS à appliquer sur l'affichage de la sélection proprement dite
 * placeholder  : texte à afficher dans la liste déroulante quand aucune sélection n'est réalisée
 * format       : précise la taille de l'affichage de la sélection
 * bgColor      : couleur de fond de l'affichage de la section
 * fgColor      : couleur d'écriture de l'affichage de la sélection
 *
 * selected     : meta attribut retournant un tableau des valeurs des options sélectionnées
 *
 * méthodes
 * --------
 * __construct(string $id)
 * __isset(string $key)
 * __get(string $key)
 * __set(string $key, $val)
 * addOption($value, array $options)
 * setOption($value, array $options)
 * rmOption($value)
 * getStateOption($value)
 * getSelectedOption()
 * enaDispBySide() : ODSelect
 * enaDispUnder() : ODSelect
 * validate_optionArray(array $option)
 */
class ODSelect extends ODContained
{
    const ODSELECTFORMAT_BIG = " big";
    const ODSELECTFORMAT_NORMAL = '';
    const ODSELECTFORMAT_SMALL = ' small';

    const ERR_BAD_FUNCTION_CALL_MSG = "l'attribut option inaccessible, veuillez utiliser les méthodes spécidfiques";

    protected static $odselect_attributes = ['object', 'typeObj', 'template', 'options', 'multiple', 'label',
        'labelClass', 'selectClass', 'placeholder', 'format', 'bgColor', 'fgColor'];

    /**
     * ODTable constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__ . '/../../../params/oobjects/odcontained/odselect/odselect.config.php';
        $properties = require $path;
        parent::__construct($id, $properties);

        $properties = $this->object_contructor($id, $properties);
        if ((int)$properties['widthBT'] === 0) {
            $properties['widthBT'] = $this->validate_widthBT(12);
        }
        $this->properties = $properties;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        switch ($key) {
            case 'option':
                throw new BadFunctionCallException(self::ERR_BAD_FUNCTION_CALL_MSG);
            case 'selected':
            case 'value':
                return !empty($this->getSelectedOption());
            default:
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
                throw new BadFunctionCallException(self::ERR_BAD_FUNCTION_CALL_MSG);
            case 'selected':
            case 'value':
                return $this->getSelectedOption();
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
            case 'multiple':
                $val = (bool)$val;
                break;
            case 'label':
            case 'labelClass':
            case 'selectClass':
            case 'placeholder':
                $val = (string)$val;
                break;
            case 'bgcolor':
            case 'fgcolor':
                $val = (string)$val;
                if (!$this->validate_By_Constants($val, "CSS_COLOR_", false)) {
                    throw new UnexpectedValueException("l'attribut de couleur passé est incorrect : ".$val );
                }
                break;
            case 'format':
                $val = $this->validate_By_Constants($val, "ODSELECTFORMAT_", self::ODSELECTFORMAT_NORMAL);
                break;
            case 'option':
                throw new BadFunctionCallException(self::ERR_BAD_FUNCTION_CALL_MSG);
            default:
                return parent::__set($key, $val);
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @param $value
     * @param array $options
     * @return $this|false
     * @throws Exception
     */
    public function addOption($value, array $options)
    {
        // inclu ou force value à la valeur transamise obligatoire
        $options['value'] = $value;
        $options = $this->validate_optionArray($options);
        if (!empty($value) && !empty($options['libel'])) {
            $properties = $this->properties;
            if (!array_key_exists('options', $properties)) {
                $properties['options'] = [];
            }
            if (!array_key_exists($value, $this->options)) {
                $this->options[$value] = $options;
                return $this;
            }
        }
        return false;
    }

    /**
     * @param $value
     * @param array $options
     * @return $this|false
     * @throws Exception
     */
    public function setOption($value, array $options)
    {
        $options['value'] = $value;
        $options = $this->validate_optionArray($options);
        if (array_key_exists($value, $this->options)) {
            $this->options[$value] = $options;
            return $this;
        }
        return false;
    }

    /**
     * @param $value
     * @return $this|false
     */
    public function rmOption($value)
    {
        $value = (string)$value;
        if (!empty($value) && array_key_exists($value, $this->options)) {
            unset($this->options[$value]);
            return $this;
        }
        return false;
    }

    /**
     * @param $value
     * @return false|string
     */
    public function getStateOption($value)
    {
        $value = (string)$value;
        if (!empty($value)) {
            $options = $this->options;
            if (array_key_exists($value, $options)) {
                if ($options[$value]['enable']) {
                    return 'enable';
                }
                if (!$options[$value]['enable']) {
                    return 'disable';
                }
            }
        }
        return false;
    }

    /**
     * @return false|array
     */
    public function getSelectedOption()
    {
        $selection = [];
        $options = $this->options;
        if (!empty($options)) {
            foreach ($options as $value => $option) {
                if ($options[$value]['selected']) {
                    $selection[] = $value;
                }
            }
            return $selection;
        }
        return false;
    }

    /**
     * @return ODSelect
     * @throws Exception
     */
    public function enaDispBySide() : ODSelect
    {
        $this->labelWidthBT = "";
        $this->selectWidthBT = "";

        return $this;
    }

    /**
     * @return ODSelect
     * @throws Exception
     */
    public function enaDispUnder() : ODSelect
    {
        $this->labelWidthBT = $this->validate_widthBT(12);
        $this->selectWidthBT = $this->validate_widthBT(12);

        return $this;
    }



    /**
     * @param array $options
     * @return array $options
     *
     * options array select option :
     * -> value,    : value to return if option is selected
     * -> libel,    : text to present of side of checkbox
     * -> selected  : to mark and say if option is already selected before show it
     * -> enable    : notice if this option can be use or not
     */
    private function validate_optionArray(array $option)
    {
        $nbParams = 0;
        foreach ($option as $key => $params) {
            switch ($key) {
                case 'value':
                case 'libel':
                    $params = (string)$params;
                    break;
                case 'selected':
                case 'enable':
                    $params = (bool)$params;
                    break;
                default:
                    throw new InvalidArgumentException("Paramètre $key incohérent dans un tableau de paramètres d'option");
            }
            $option[$key] = $params;
            $nbParams++;
        }
        if (!array_key_exists('selected', $option)) {
            $option['selected'] = false;
            $nbParams++;
        }
        if (!array_key_exists('enable', $option)) {
            $option['enable'] = true;
            $nbParams++;
        }
        if ($nbParams != 4) {
            throw new InvalidArgumentException("Nombre de Paramètre incohérent, attendu 4, trouvé " + count($option));
        }

        return $option;
    }
}