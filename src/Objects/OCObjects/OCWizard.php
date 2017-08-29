<?php

namespace GraphicObjectTemplating\Objects\OCObjects;

use GraphicObjectTemplating\Objects\OSContainer;

/**
 * Class OCWizard
 * @package GraphicObjectTemplating\Objects\OCObjects
 *
 * setTitle
 * getTitle
 * addEtape
 * removeEtape
 * getEtape
 * setEtape
 * getEtapes
 * setEtapes
 * setEtapeEnCours
 * getEtapeEnCours
 * setShowBtnWizzard
 * getShowBtnWizzard
 */
class OCWizard extends OSContainer
{
    const BTNWIZARD_SHOWALWAYS = "showAlways";
    const BTNWIZARD_SHOWEND    = "showEnd";

    const const_btnWizard      = "";

    public function __construct($id)
    {
        parent::__construct($id, "oobject/ocobjects/ocwizard/ocwizard.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function setTitle($title = "")
    {
        $title = (string) $title;
        if (!empty($title)) {
            $properties = $this->getProperties();
            $properties['tilte'] = $title;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getTitle()
    {
        $properties = $this->getProperties();
        return (array_key_exists('title', $properties)) ? $properties['title'] : false;
    }

    public function addEtape($title, $callback = "", OSContainer $content, $actif = true)
    {// ajout d'une étape en fin de tableau
        $title    = (string) $title;
        $callback = (string) $callback;
        $actif    = ($actif === true);

        $properties = $this->getProperties();
        if (!isset($properties['etapes'])) $properties['etapes'] = [];
        $ord = sizeof($properties['etapes']) + 1;

        $properties['etapes'][$ord]             = [];
        $properties['etapes'][$ord]['title']    = $title;
        $properties['etapes'][$ord]['content']  = $content;
        $properties['etapes'][$ord]['callback'] = $callback;
        $properties['etapes'][$ord]['actif']    = $actif;
        $this->setProperties($properties);
        return $this;
    }

    public function removeEtape($ord)
    {
        $ord = (int) $ord;
        if ($ord > 0) {
            $properties = $this->getProperties();
            if (array_key_exists($ord, $properties['etapes'])) {
                $maxOrd = sizeof($properties['etapes']);
                unset($properties['etapes'][$ord]);
                for ($i=$ord +1; $i <= $maxOrd; $i++ ) {
                    // reséquencement du restant
                    $properties['etapes'][$i-1] = $properties[$i];
                }
                return $this;
            }
        }
        return false;
    }

    public function setEtape($ord, $title, $callback = "", OSContainer $content, $actif = true)
    { // mise à jour d'une étape déjà déclarée
        $ord = (int) $ord;
        $properties = $this->getProperties();
        if ($ord < 1 || $ord > sizeof($properties['etapes'])) {
            return false;
        } else {
            $properties['etapes'][$ord]             = [];
            $properties['etapes'][$ord]['title']    = $title;
            $properties['etapes'][$ord]['content']  = $content;
            $properties['etapes'][$ord]['callback'] = $callback;
            $properties['etapes'][$ord]['actif']    = $actif;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function getEtape($ord)
    {
        $ord = (int) $ord;
        $properties         = $this->getProperties();
        $maxOrd = sizeof($properties['etapes']);
        if ($ord > 1 && $ord <= $maxOrd) return $properties['etapes'][$ord];
        return false;
    }

    public function getEtapes()
    {
        $properties         = $this->getProperties();
        return ((!empty($properties['etapes'])) ? $properties['etapes'] : false) ;
    }

    public function setEtapes(array $etapes)
    {
        $properties = $this->getProperties();
        $properties['etapes'] = $etapes;
        $this->setProperties($properties);
        return $this;
    }

    public function setEtapeEnCours($etape)
    {
        $etape = (int) $etape;
        $properties = $this->getProperties();
        $properties['etapeEnCours'] = $etape;
        $this->setProperties($properties);
        return $properties;
    }

    public function getEtapeEnCours()
    {
        $properties         = $this->getProperties();
        return ((!empty($properties['etapeEnCours'])) ? $properties['etapeEnCours'] : false) ;
    }

    public function setShowBtnWizard($show = self::BTNWIZARD_SHOWALWAYS)
    {
        $btnWizards = $this->getBtnWizardConstants();
        if (!in_array($show, $btnWizards)) $show = self::BTNWIZARD_SHOWALWAYS;

        $properties = $this->getProperties();
        $properties['btnWizard'] = $show;
        $this->setProperties($properties);
        return $properties;
    }

    public function getShowBtnWizard()
    {
        $properties         = $this->getProperties();
        return ((!empty($properties['btnWizard'])) ? $properties['btnWizard'] : false) ;
    }

    /*
     * méthode interne à la classe OObject
     */

    private function getBtnWizardConstants()
    {
        $retour = [];
        if (empty($this->const_btnWizard)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'BTNWIZARD');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_btnWizard = $retour;
        } else {
            $retour = $this->const_btnWizard;
        }
        return $retour;
    }

}