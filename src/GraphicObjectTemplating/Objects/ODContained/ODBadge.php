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
    const BADGE_GREENDARK  = "badge bg-color-greenDark";
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
    const BADGE_REDLIGHT   = "badge bg-color-redLight";

    const BADGE_COLOR_DEFAULT = "badge bg-color-white";
    const BADGE_COLOR_DANGER  = "badge bg-color-red";
    const BADGE_COLOR_WARNING = "badge bg-color-orange";
    const BADGE_COLOR_INFO    = "badge bg-color-blue";
    const BADGE_COLOR_PRIMARY = "badge bg-color-blueMedium";
    const BADGE_COLOR_SUCCES  = "badge bg-color-greenMedium";

    const BADGEPOS_LEFT   = "pull-left";
    const BADGEPOS_CENTER = "pull-center";
    const BADGEPOS_RIGHT  = "pull-right";

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

    public function evtClick($callback)
    {
        $callback               = (string) $callback;
        $properties             = $this->getProperties();
        if(!isset($properties['event'])) $properties['event'] = [];
        if(!is_array($properties['event'])) $properties['event'] = [];

        $properties['event']['click'] = $callback;
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


    public function setPosition($position = self::RIGHT)
    {
        $properties = $this->getProperties();
        $properties['position'] = $position;
        $this->setProperties($properties);
        return $this;
    }

    public function getPosition()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['position'])) ? $properties['posirion'] : false) ;
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