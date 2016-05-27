<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 26/02/16
 * Time: 17:10
 */

namespace GraphicObjectTemplating\Objects\ODContent;

use GraphicObjectTemplating\Objects\ODContent;
use Zend\Config\Config;

/**
 * Class OCButton
 * @package GraphicObjectTemplating\Objects\ODContent
 *
 * type des boutons : (affecté suivant d'autre attribut)
 *      CUSTOM  = bouton divers permettant de déclencher un action
 *      SUBMIT  = bouton de déclenchement du pseudo formulaire auquel il est lié
 *      RESET   = bouton de réinitialisation du pseudo formulaire auquel il est lié
 * l'état du bouton est donné par l'attribut 'state'
 *      ENABLE  = bouton actif (peut déclencher une action par un clic)
 *      DISABLE = bouton déactivé (n'est plus accessible
 *
 * setLabel
 * getLabel
 * enable
 * disable
 * getState
 * setIcon
 * getIcon
 * setForm
 * setType
 * getType
 * evtClick
 * disClick
 * setNature
 * getNature
 */
class OCButton extends ODContent
{
    const TYPE_CUSTOM   = "custom";
    const TYPE_SUBMIT   = "submit";
    const TYPE_RESET    = "reset";
    const TYPE_LINK     = "link";

    const STATE_ENABLE  = true;
    const STATE_DISABLE = false;

    const NATURE_DEFAULT = 'btn btn-default';
    const NATURE_PRIMARY = 'btn btn-primary';
    const NATURE_SUCCESS = 'btn btn-success';
    const NATURE_INFO    = 'btn btn-info';
    const NATURE_WARNING = 'btn btn-warning';
    const NATURE_DANGER  = 'btn btn-danger';
    const NATURE_LINK    = 'btn btn-link';

    public function __construct($id) {
        parent::__construct($id, "oobject/odcontent/ocbutton/ocbutton.config.phtml");
        $this->setDisplay();
    }

    public function setLabel($label)
    {
        $label               = (string) $label;
        $properties          = $this->getProperties();
        $properties['label'] = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['label'])) ? $properties['label'] : false) ;
    }

    public function enable()
    {
        $properties          = $this->getProperties();
        $properties['state'] = self::STATE_ENABLE;
        $this->setProperties($properties);
        return $this;
    }

    public function disable()
    {
        $properties          = $this->getProperties();
        $properties['state'] = self::STATE_DISABLE;
        $this->setProperties($properties);
        return $this;
    }

    public function getState()
    {
        $properties          = $this->getProperties();
        return (($properties['state'] === true) ? 'enable' : 'disable');
    }

    public function setIcon($icon)
    {
        $icon               = (string) $icon;
        $properties         = $this->getProperties();
        $properties['icon'] = $icon;
        $this->setProperties($properties);
        return $this;
    }

    public function getIcon()
    {
        $properties         = $this->getProperties();
        return ((!empty($properties['icon'])) ? $properties['icon'] : false) ;
    }

    public function setForm($form)
    {
        parent::setForm($form);

        $properties = $this->getProperties();
        $callback   = $properties['callback'];
        switch(true) {
            case (empty($callback)):
                $properties['type'] = self::TYPE_RESET;  break;
            case (!empty($callback)):
                $properties['type'] = self::TYPE_SUBMIT; break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function setType($type = self::TYPE_CUSTOM)
    {
        $types = $this->getTypesConstants();
        $type  = (string) $type;
        if (!in_array($type, $types)) $type = self::TYPE_CUSTOM;

        if ($type == self::TYPE_LINK) {
            if (isset($properties['event']['click']))
                $properties['event']['click'] = mb_strtolower($properties['event']['click']);
        }

        $properties         = $this->getProperties();
        $properties['type'] = $type;
        $this->setProperties($properties);
        return $this;
    }

    public function getType() {
        $properties         = $this->getProperties();
        return ((!empty($properties['type'])) ? $properties['type'] : false) ;
    }

    public function evtClick($callback)
    {
        $callback               = (string) $callback;
        $properties             = $this->getProperties();
        if(!isset($properties['event'])) $properties['event'] = [];
        if(!is_array($properties['event'])) $properties['event'] = [];
        $properties['event']['click'] = $callback;

        $form     = $properties['form'];
        switch(true) {
            case (empty($form)):
                $properties['type'] = self::TYPE_CUSTOM; break;
            case (!empty($form)):
                $properties['type'] = self::TYPE_SUBMIT; break;
        }
        
        if (isset($properties['type']) && ($properties['type'] == self::TYPE_LINK)) {
            $properties['event']['click'] = mb_strtolower($callback);
        }

        $this->setProperties($properties);
        return $this;
    }

    public function disClick()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']['click'])) unset($properties['event']['click']);
        $this->setProperties($properties);
        return $this;
    }

    public function setNature($nature = self::NATURE_DEFAULT)
    {
        $natures = $this->getNatureConst();
        if (!in_array($nature, $natures)) $nature = self::NATURE_DEFAULT;

        $properties = $this->getProperties();
        $properties['nature'] = $nature;
        $this->setProperties($properties);
        return $this;
    }

    public function getNature() {
        $properties         = $this->getProperties();
        return ((!empty($properties['nature'])) ? $properties['nature'] : false) ;
    }

    /*
     * méthode interne à la classe OObject
     */

    private function getTypesConstants()
    {
        $constants = $this->getConstants();
        foreach ($constants as $key => $constant) {
            $pos = strpos($key, 'TYPE_');
            if ($pos === false) unset($constants[$key]);
        }
        return $constants;
    }

    private function getNatureConst()
    {
        $constants = $this->getConstants();
        foreach ($constants as $key => $constant) {
            $pos = strpos($key, 'NATURE');
            if ($pos === false) unset($constants[$key]);
        }
        return $constants;
    }
}