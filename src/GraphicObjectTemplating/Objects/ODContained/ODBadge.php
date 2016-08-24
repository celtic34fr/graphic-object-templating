<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 24/08/16
 * Time: 15:06
 */

namespace GraphicObjectTemplating\Objects\ODContained;


use GraphicObjectTemplating\Objects\ODContained;

class ODBadge extends ODContained
{
    const BADGE_DEFAULT    = "badge";
    const BADGE_BLUE       = "badge bg-color-blue";
    const BADGE_BLUEDARK   = "badge bg-color-blueLight";
    const BADGE_GREEN      = "badge bg-color-green";
    const BADGE_GREENLIGHT = "badge bg-color-greenLight";
    const BADGE_GEENDARK   = "badge bg-color-greenDark";
    const BADGE_RED        = "badge bg-color-red";
    const BADGE_YELLOW     = "badge bg-color-yellow";
    const BADGE_ORANGE     = "badge bg-color-orange";
    const BADGE_ORANGEDARK = "badge bg-color-orangeDark";
    const BADGE_PINK       = "badge bg-color-pink";
    const BADGE_PINKDARK   = "badge bg-color-pinkDark";
    const BADGE_PURPLE     = "badge bg-color-purple";
    const BADGE_DARKEN     = "badge bg-color-darken";
    const BADGE_LIGHTEN    = "badge bg-color-lighten";
    const BADGE_WHITE      = "badge bg-color-white";
    const BADGE_GRAYDARK   = "badge bg-color-grayDark";
    const BADGE_MAGENTA    = "badge bg-color-magenta";
    const BADGE_TEAL       = "badge bg-color-teal";
    const BADGE_REDLIGHT   = "badge  bg-color-redLight";

    public function __construct($id) {
        $parent = parent::__construct($id, "oobject/odcontained/odbadge/odbadge.config.phtml");
        $this->properties = $parent->properties;
        $this->setDisplay();
    }

    public function setWidthBT($widthBT)
    {
        // surcharge de méthode pour qu'elle ne fasse rien
        return $this;
    }

    public function getWidthBT()
    {
        // surcharge de méthode pour qu'elle ne fasse rien
        return $this;
    }

    public function setStyle($style)
    {
        // surcharge de méthode pour qu'elle ne fasse rien
        return $this;
    }

    public function getStyle()
    {
        // surcharge de méthode pour qu'elle ne fasse rien
        return $this;
    }

    public function setColor($color = null)
    {
        if (!empty($color)) {
            $colors = $this->getBadgeColorConst();
            $color  = (string) $color;
            if (!in_array($color, $colors)) $color = self::BADGE_DEFAULT;

            $properties = $this->getProperties();
            $properties['badgeColor'] = $color;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getColor()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['badgeColor'])) ? $properties['badgeColor'] : false) ;
    }

    private function getBadgeColorConst()
    {
        $retour = [];
        if (empty($this->const_nature)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'BADGE');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_nature = $retour;
        } else {
            $retour = $this->const_nature;
        }
        return $retour;
    }

}