<?php


namespace GraphicObjectTemplating\OObjects\OSContainer;


use GraphicObjectTemplating\OObjects\OSContainer;

/**
 * Class OSDiv
 * @package GraphicObjectTemplating\OObjects\OSContainer
 *
 * méthodes
 * --------
 * __construct($id)
 */
class OSDiv extends OSContainer
{
    protected static $osdiv_attributes = [];

    public function __construct($id)
    {
        $path = __DIR__ . '/../../../params/oobjects/oscontainer/osdiv/osdiv.config.php';
        $properties = require $path;

        parent::__construct($id, $properties);

        $properties = $this->object_contructor($id, $properties);
        $this->properties = $properties;

        if ((int)$this->widthBT ===0 ) {
            $this->widthBT = 12;
        }
    }
}