<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 10:49
 */
namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODInput
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * setType          : affecte le type de saisie
 *      TEXT        : mode texte
 *      PASSWORD    : saisie de mot de passe (caché, présence d'étoile à la place)
 *      HIDDEN      : variable cachée
 *      NUMBER      : chiffre entier
 * getType          : restitue le type de saisie
 * setSize          : fixe le nombre de caractères (maximum) à afficher
 * getSize          : restitue le nombre maximal de caractères à afficher
 * setMaxlength     : fixe le nombre de caractères (maximum) à saisir
 * getMaxlength     : restitue le nombre maximal de caractères à saisir
 * setLabel         : attribut un label, une étiquette à la zone de saisie
 * getLabel         : restitue le label, l'étiquette affectée à la zone de saisie
 * setPlaceholder   : affecte le texte à montyer quand la zone de saisie est vide (linvite de saisie)
 * getPlaceholder   : restitue le texte affiché quand la zone de saisie est vide
 * setLabelWidthBT  : attribut une largeur (Bootstrap Twitter) au label (tableau de valeur en rapport des 4 médias gérés)
 * getLabelWidthBT  : restitue la largeur (Bootstrap Twitter) du label (tableau de valeur en rapport des 4 médias gérés)
 * evtChange        : évènement changement de valeur, paramètre callback
 *      callback : "nomModule/nomObjet/nomMéthode"
 *          si nomObjet contient 'Controller' -> "nomModule/Controller/nomObjet/nomMéthode"
 *          si nomModule == 'Object' :
 *              si nomObjet commence par 'OD' -> "GraphicObjectTemplating/Objects/ODContained/nomObjet/nomMéthode"
 *              si nomObjet commence par 'OS' -> "GraphicObjectTemplating/Objects/ODContainer/nomObjet/nomMéthode"
 * disChange        : désactivation de l'évènement changement de valeur
 * evtKeyup         : évènement touche frappée (à chaque saisie de caractère), paramètre callback
 *      callback : "nomModule/nomObjet/nomMéthode"
 *          si nomObjet contient 'Controller' -> "nomModule/Controller/nomObjet/nomMéthode"
 *          si nomModule == 'Object' :
 *              si nomObjet commence par 'OD' -> "GraphicObjectTemplating/Objects/ODContained/nomObjet/nomMéthode"
 *              si nomObjet commence par 'OS' -> "GraphicObjectTemplating/Objects/ODContainer/nomObjet/nomMéthode"
 * disKeyup         : désactivation de l'évènement touche frappée
 * setIcon          : affecte une icône après le label (font awesome / glyphicon)
 * getIcon          : récupère le nom de l'icône affecté après le label
 * setWide
 * getWide
 */
class ODInput extends ODContained
{
    const INPTYPE_TEXT     = "text";
    const INPTYPE_PASSWORD = "password";
    const INPTYPE_HIDDEN   = 'hidden';
    const INPTYPE_NUMBER   = 'number';

    protected $const_inpType;

    public function __construct($id) {
        parent::__construct($id, "oobject/odcontained/odinput/odinput.config.phtml");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function setType($type = self::INPTYPE_TEXT) {
        $types = $this->getInpTypesConstants();
        $type = (string) $type;
        if (!in_array($type, $types)) $type = self::INPTYPE_TEXT;

        $properties         = $this->getProperties();
        $properties['type'] = $type;
        $this->setProperties($properties);
        return $this;
    }

    public function getType() {
        $properties            = $this->getProperties();
        return ((array_key_exists('type', $properties)) ? $properties['type'] : false);
    }

    public function setSize($size = 0)
    {
        $size = (int) $size;
        if ($size < 0) $size = 0;

        $properties         = $this->getProperties();
        $properties['size'] = $size;
        $this->setProperties($properties);
        return $this;
    }

    public function getSize()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('size', $properties)) ? $properties['size'] : false);
    }

    public function setMaxlength($maxlength = 0)
    {
        $maxlength = (int) $maxlength;
        if ($maxlength < 0) $maxlength = 0;

        $properties              = $this->getProperties();
        $properties['maxlength'] = $maxlength;
        $this->setProperties($properties);
        return $this;
    }

    public function getMaxlength()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('maxlength', $properties)) ? $properties['maxlength'] : false);
    }

    public function setLabel($label)
    {
        $label = (string) $label;
        $properties          = $this->getProperties();
        $properties['label'] = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('label', $properties)) ? $properties['label'] : false);
    }

    public function setPlaceholder($placeholder)
    {
        $placeholder = (string) $placeholder;
        $properties                = $this->getProperties();
        $properties['placeholder'] = $placeholder;
        $this->setProperties($properties);
        return $this;
    }

    public function getPlaceholder()
    {
        $properties                = $this->getProperties();
        return ((!empty($properties['placeholder'])) ? $properties['placeholder'] : false) ;
    }

    public function setLabelWidthBT($widthBT)
    {
        $lxs = 0; $ixs = 0;
        $lsm = 0; $ism = 0;
        $lmd = 0; $imd = 0;
        $llg = 0; $ilg = 0;

        switch (true) {
            case (is_numeric($widthBT)):
                $lxs = $widthBT; $ixs = 12 - $widthBT;
                $lsm = $widthBT; $ism = 12 - $widthBT;
                $lmd = $widthBT; $imd = 12 - $widthBT;
                $llg = $widthBT; $ilg = 12 - $widthBT;
                break;
            default:
                /** widthBT chaîne de caractères */
                $widthBT = explode(":", $widthBT);
                foreach ($widthBT as $item) {
                    $key = strtoupper(substr($item, 0, 2));
                    switch ($key) {
                        case "WX" : $lxs = intval(substr($item,2)); break;
                        case "WS" : $lsm = intval(substr($item,2)); break;
                        case "WM" : $lmd = intval(substr($item,2)); break;
                        case "WL" : $llg = intval(substr($item,2)); break;
                        default:
                            if (substr($key,0,1) == "W") {
                                $wxs = intval(substr($item,1));
                                $wsm = intval(substr($item,1));
                                $wmd = intval(substr($item,1));
                                $wlg = intval(substr($item,1));
                            }
                    }
                }
                $ixs = 12 - $lxs;
                $ism = 12 - $lsm;
                $imd = 12 - $lmd;
                $ilg = 12 - $llg;
                break;
        }
        $properties = $this->getProperties();
        $properties['labelWidthBT']['lxs'] = $lxs;
        $properties['labelWidthBT']['lsm'] = $lsm;
        $properties['labelWidthBT']['lmd'] = $lmd;
        $properties['labelWidthBT']['llg'] = $llg;
        $properties['labelWidthBT']['ixs'] = $ixs;
        $properties['labelWidthBT']['ism'] = $ism;
        $properties['labelWidthBT']['imd'] = $imd;
        $properties['labelWidthBT']['ilg'] = $ilg;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabelWidthBT()
    {
        $properties                = $this->getProperties();
        return ((!empty($properties['labelWidthBT'])) ? $properties['labelWidthBT'] : false) ;
    }

    public function evtChange($callback)
    {
        $properties = $this->getProperties();
        if(!isset($properties['event'])) $properties['event']= [];
        $properties['event']['change'] = $callback;
        $this->setProperties($properties);
        return $this;
    }

    public function disChange()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']['change'])) unset($properties['event']['change']);
        $this->setProperties($properties);
        return $this;
    }

    public function evtKeyup($callback)
    {
        $properties = $this->getProperties();
        if(!isset($properties['event'])) $properties['event']= [];
        $properties['event']["keyup"] = $callback;
        $this->setProperties($properties);
        return $this;
    }

    public function disKeyup()
    {
        $properties             = $this->getProperties();
        if (isset($properties['event']["keyup"])) unset($properties['event']["keyup"]);
        $this->setProperties($properties);
        return $this;
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

    public function setWide($wide)
    {
        $wide               = (int) $wide;
        $properties         = $this->getProperties();
        $properties['wide'] = $wide;
        $this->setProperties($properties);
        return $this;
    }

    public function getWide()
    {
        $properties         = $this->getProperties();
        return ((!empty($properties['wide'])) ? $properties['wide'] : false) ;
    }

    /*
     * méthode interne à la classe OObject
     */

    private function getInpTypesConstants()
    {
        $retour = [];
        if (empty($this->const_inpType)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TYPE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_inpType = $retour;
        } else {
            $retour = $this->const_inpType;
        }
        return $retour;
    }

}