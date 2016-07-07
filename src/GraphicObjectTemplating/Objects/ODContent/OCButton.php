<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 26/02/16
 * Time: 17:10
 */

namespace GraphicObjectTemplating\Objects\ODContent;

use GraphicObjectTemplating\Objects\ODContent;

/**
 * Class OCButton
 * @package GraphicObjectTemplating\Objects\ODContent
 *
 * type des boutons : (affecté suivant d'autre attribut)
 *      CUSTOM  = bouton divers permettant de déclencher un action
 *      SUBMIT  = bouton de déclenchement du pseudo formulaire auquel il est lié
 *      RESET   = bouton de réinitialisation du pseudo formulaire auquel il est lié
 *
 * setLabel     : affectation du texte présenté dans le bouton
 * getLabel     : récupération du texte présenté dans le bouton
 * setIcon      : affecte une icône au bouton (font awesome / glyphicon)
 * getIcon      : récupère le nom de l'icône affecté au bouton
 * setForm      : surchange de la méthode d'affectation de l'identifiant de regroupement (simulation de formulaire)
 * setType      : affectation du type de bouton
 *      CUSTOM      : type divers
 *      SUBMIT      : type soumission (de formuulaire)
 *      RESET       : type remise à zéro des champs (de formulaire)
 *      LINK        : type lien HTML
 * getType      : récupération du type du bouton
 * evtClick     : activation et paramètrage de l'évènement 'click' sur le bouton
 * disClick     : désactivation de lm'évènement 'click' sur le bouton
 * setNature    : affectation de la nature du bouton
 *      DEFAULT     : nature par défaut (valeur par défaut)
 *      PRIMARY     : nature primaire (bleu roi)
 *      SUCCESS     : nature succès (vert)
 *      INFO        : nature information (gris bleu)
 *      WARNING     : nature avertissement alerte (orange)
 *      DANGER      : nature danger, erreur (rouge)
 *      LINK        : nature lien (lien HTML, plus bouton alors)
 * getNature
 */
class OCButton extends ODContent
{
    const TYPE = array(
        'CUSTOM'   => "custom",
        'SUBMIT'   => "submit",
        'RESET'    => "reset",
        'LINK'     => "link");

    const NATURE = array(
        'DEFAULT' => 'btn btn-default',
        'PRIMARY' => 'btn btn-primary',
        'SUCCESS' => 'btn btn-success',
        'INFO'    => 'btn btn-info',
        'WARNING' => 'btn btn-warning',
        'DANGER'  => 'btn btn-danger',
        'LINK'    => 'btn btn-link');

    public function __construct($id) {
        $parent = parent::__construct($id, "oobject/odcontent/ocbutton/ocbutton.config.phtml");
        $this->properties = $parent->properties;
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
                $properties['type'] = self::TYPE['RESET'];  break;
            case (!empty($callback)):
                $properties['type'] = self::TYPE['SUBMIT']; break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function setType($type = self::TYPE['CUSTOM'])
    {
        $types = $this->getTypesConstants();
        $type  = (string) $type;
        if (!in_array($type, $types)) $type = self::TYPE['CUSTOM'];

        if ($type == self::TYPE['LINK']) {
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
                $properties['type'] = self::TYPE['CUSTOM']; break;
            case (!empty($form)):
                $properties['type'] = self::TYPE['SUBMIT']; break;
        }
        
        if (isset($properties['type']) && ($properties['type'] == self::TYPE['LINK'])) {
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

    public function setNature($nature = self::NATURE['DEFAULT'])
    {
        $natures = $this->getNatureConst();
        if (!in_array($nature, $natures)) $nature = self::NATURE['DEFAULT'];

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
        return self::TYPE;
    }

    private function getNatureConst()
    {
        return self::NATURE;
    }
}