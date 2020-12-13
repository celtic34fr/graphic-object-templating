<?php


namespace GraphicObjectTemplating\OObjects\OSContainer;


use BadFunctionCallException;
use Exception;
use GraphicObjectTemplating\OObjects\ODContained\ODButton;
use GraphicObjectTemplating\OObjects\OObject;
use InvalidArgumentException;
use ReflectionException;
use UnexpectedValueException;

/**
 * Class OSForm
 * @package GraphicObjectTemplating\OObjects\OSContainer
 *
 * méthodes
 * --------
 * pubf __construct($id)
 * pubf constructor($id, $properties) : array
 * pubf __isset(string $key): bool
 * pubf __get(string $key)
 * pubf __set(string $key, $val)
 * pubf alter_btnsControls(string $val, array $properties) : array
 * prif getTypeBtnConstants() : array
 * prif getDispBtnConstants() : array
 * pubf addChild(OObject $child, string $mode = self::MODE_LAST, $params = null, $required = false)
 * prif validate_dispBtns($val) : string
 * pubf addBtnCtrl(ODButton $btn, int $ord)
 * prif validate_btnType(ODButton $btn) : bool
 */
class OSForm extends OSDiv
{
    const TYPE_BTN_RESET = 'reset';
    const TYPE_BTN_SUBMIT = 'submit';

    const DISP_BTN_HORIZONTAL = 'H';
    const DISP_BTN_VERTICAL = 'V';

    protected static array $const_type_btn;
    protected static array $const_disp_btn;

    const O1W2 = 'O1:W2';
    const O1W3 = 'O1:W3';
    const O1W4 = 'O1:W4';
    const O1W10 = "O1:W10";
    const O2W4 = 'O2:W4';

    /**
     * OSForm constructor.
     * @param $id
     */
    public function __construct($id)
    {
        parent::__construct($id);

        $path = __DIR__ . '/../../../params/oobjects/oscontainer/osform/osform.config.php';
        $properties = require $path;
        $properties = $this->object_contructor($id, $properties);

        $properties['btnsControls']->id = $properties['id'].'Ctrls';

        $this->properties = $properties;

        if ((int) $this->btnsWidthBT === 0) {
            $this->btnsWidthBT = 2;
        }
        if ((int)$this->widthBT === 0) {
            $this->widthBT = 12;
        }
        if ((int)$this->widthBTbody === 0) {
            $this->widthBTbody = 12;
        }
        if ((int)$this->widthBTctrls === 0) {
            $this->widthBTctrls = 12;
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        $rslt = parent::__isset($key);
		if ($rslt === false) {
            $rslt = $this->btnsControls->__isset($key);
        }
		return $rslt;
    }

    /**
     * @param string $key
     * @return mixed|null
     * @throws \Exception
     */
    public function __get(string $key)
    {
        $rslt = parent::__get($key);
		if ($rslt === false) {
            $rslt = $this->btnsControls->__get($key);
        }
		return $rslt;
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
            case 'widthBTbody':
            case 'widthBTctrls':
            case 'btnsWidthBT':
                $val = $this->validate_widthBT($val);
                break;
            case 'btnsDisplay':
                $val = $this->validate_dispBtns($val);
                if ($val !== $this->properties['btnsDisplay']) {
                    $this->properties = $this->alter_btnsControls($val, $this->properties);
                }
                break;
			case 'btnsControls':
				throw new BadFunctionCallException("Impossible d'affecter direment un bouton de contrôle, passez par les méthodes spéciales");
				break;
            default:
                return parent::__set($key, $val);
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @param string $val
     * @param array $properties
     * @return array
     */
    public function alter_btnsControls(string $val, array $properties) : array
    {
        $btnsControls = $properties['btnsControls'];
        $children = $btnsControls->children;
        $countChildren = 0;
        switch ($val) {
            case self::DISP_BTN_VERTICAL:
                foreach ($children as $idChild => $child) {
                    $child->widthBT = 12;
                    $children[$idChild] = $child;
                }
                $btnsControls->children = $children;
                break;
            case self::DISP_BTN_HORIZONTAL:
                $widthBT = [];
                switch (count($children)){
                    case 1:
                        $widthBT[1] = $this->validate_widthBT(self::O1W10);
                        break;
                    case 2:
                        $widthBT[1] = $this->validate_widthBT(self::O1W4);
                        $widthBT[2] = $this->validate_widthBT(self::O2W4);
                        break;
                    case 3:
                        $widthBT[1] = $this->validate_widthBT(self::O1W3);
                        $widthBT[2] = $this->validate_widthBT(self::O1W3);
                        break;
                    default:
                        $widthBT[1] = $this->validate_widthBT(self::O1W2);
                        $widthBT[2] = $this->validate_widthBT(self::O1W2);
                        break;
                }
                foreach ($children as $idChild => $child) {
                    $countChildren += 1;
                    $child->widthBT = $widthBT[($countChildren<2) ? 1 : 2];
                    $children[$idChild] = $child;
                }
                break;
            default:
                throw new UnexpectedValueException("Boutons de controle : mode de présentation ($val) inconnu");
                break;
        }
        $properties['btnsControls'] = $btnsControls;
        return $properties;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getTypeBtnConstants() : array
    {
        $retour = [];
        if (empty(self::$const_type_btn)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'TYPE_BTN_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_type_btn = $retour;
        } else {
            $retour = self::$const_type_btn;
        }
        return $retour;
    }

    /**
     * @return array
     * @throws ReflectionException
     */
    private function getDispBtnConstants() : array
    {
        $retour = [];
        if (empty(self::$const_disp_btn)) {
            foreach (self::getConstants() as $key => $constant) {
                $pos = strpos($key, 'DISP_BTN_');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            self::$const_disp_btn = $retour;
        } else {
            $retour = self::$const_disp_btn;
        }
        return $retour;
    }

    /**
     * @param OObject $child
     * @param string $mode
     * @param null $params
     * @param false $required
     * @throws \Exception
     */
    public function addChild(OObject $child, string $mode = self::MODE_LAST, $params = null, $required = false)
    {
        $child->form = $this->id;
        parent::addChild($child, $mode, $params);
        $require = $this->required;
        if ($required) {
            $require[$child->id] = true;
        } else {
            $require[$child->id] = false;
        }
        $this->required = $require;
    }

    /**
     * @param $val
     * @return string
     * @throws ReflectionException
     */
    private function validate_dispBtns($val) : string
    {
        return (in_array($val, $this->getDispBtnConstants())) ? $val : self::DISP_BTN_HORIZONTAL;
    }

    /**
     * @param ODButton $btn
     * @param int $ord
     * @throws Exception
     */
    public function addBtnCtrl(ODButton $btn, int $ord)
    {
        $properties = $this->properties;
        $btnCtrls = $properties['btnsControls'];
        $btnChildren = $btnCtrls->children;
        /** contrôle numéro d'ordre */
        if (array_key_exists($ord, $btnChildren)) {
            throw new InvalidArgumentException("Numéro d'ordre " . $ord . " déjà attribué");
        }
        /** contrôle nature btn en fonction de ceux déjà affectés */
        if (!$this->validate_btnType($btn)) {
            throw new UnexpectedValueException("Impossible d'avoir 2 boutons de type RESET");
        }

        if ($btn->type === ODButton::BUTTONTYPE_RESET) {
            $btn->setEvent('click', ['class' => 'javascript:', 'method' => 'resetFormDatas("' . $this->id . '")',
                'stopEvent' => true]);
        }
        $btn->classes = "ospaddingV05";
        $btn->form = $this->id;

        if ($this->btnsDisplay === self::DISP_BTN_HORIZONTAL) {
            $widthBT = [];
            switch (count($btnChildren)) {
                case 0:
                    $widthBT[1] = self::O1W10;
                    $widthBT[2] = '';
                    break;
                case 1:
                    $widthBT[1] = self::O1W4;
                    $widthBT[2] = self::O2W4;
                    break;
                case 2:
                    $widthBT[1] = self::O1W3;
                    $widthBT[2] = self::O1W3;
                    break;
                default:
                    $widthBT[1] = self::O1W2;
                    $widthBT[2] = self::O1W2;
                    break;
            }
            if ($ord === 1) {
                $btn->widthBT = $widthBT[1];
            } else {
                $btn->widthBT = $widthBT[2];
            }
            foreach ($btnChildren as $ind => $cBtn) {
                if ($ind === 1) {
                    $cBtn->widthBT = $widthBT[1];
                } else {
                    $cBtn->widthBT = $widthBT[2];
                }
                $btnChildren[$ind] = $cBtn;
            }
        } else {
            $btn->widthBT = 12;
        }

        $btnChildren[$ord] = $btn;
        $btnCtrls->children = $btnChildren;
        $properties['btnsControls'] = $btnCtrls;
        $this->properties = $properties;

        return $btn;
    }

    /**
     * @param ODButton $btn
     * @return bool
     */
    private function validate_btnType(ODButton $btn) : bool
    {
        $btnType = $btn->type;
        foreach ($this->btnsControls as $btnC) {
            if ($btnC instanceof ODButton && $btnC->type === ODButton::BUTTONTYPE_RESET && $btnType === ODButton::BUTTONTYPE_RESET) {
                return false;
            }
        }
        return true;
    }
}