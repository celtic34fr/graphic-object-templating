<?php


namespace GraphicObjectTemplating\OObjects\OSContainer;


use GraphicObjectTemplating\OObjects\OSContainer;

class OSDiv extends OSContainer
{
    public function __construct($id)
    {
        $path = __DIR__ . '/../../../params/oobjects/oscontainer/osdiv/osdiv.config.php';
        $properties = require $path;

        parent::__construct($id, $properties);

        $properties = $this->constructor($id, $properties);
        $this->properties = $properties;

        if ((int)$this->widthBT ===0 ) $this->widthBT = 12;
    }

    /**
     * @param string $id
     * @param array $properties
     * @return array
     */
    public function constructor($id, $properties) : array
    {
        $properties = parent::constructor($id, $properties);

        $typeObj = $properties['typeObj'];
        $object = $properties['object'];
        $template = $properties['template'];

        list($properties['template'], $properties['className']) = $this->set_Termplate_ClassName($typeObj, $object, $template);
        return $properties;
    }
}