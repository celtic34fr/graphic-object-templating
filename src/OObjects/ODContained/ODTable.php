<?php

use GraphicObjectTemplating\OObjects\ODContained;

class ODTable extends ODContained
{
    /**
     * ODTable constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__ . '/../../../params/oobjects/odcontained/odtable/odtable.config.php';
        $properties = require $path;
        parent::__construct($id, $properties);

        $properties = $this->object_contructor($id, $properties);
        if ((int)$properties['widthBT'] === 0) {
            $properties['widthBT'] = $this->validate_widthBT(12);
        }
        $this->properties = $properties;
    }

    /**
     * @param string $id
     * @param array|null $properties
     * @return array
     */
    public function constructor(string $id, array $properties = null): array
    {
        $properties = parent::constructor($id, $properties);
        $btnsActions = $properties['btnsActions'];
        $btnsActions->id = $this->id . 'BtnsActions';
        $properties['btnsActions'] = $btnsActions;

        return $this->object_contructor($id, $properties);
    }
}