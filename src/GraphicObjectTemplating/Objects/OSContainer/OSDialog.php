<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 03/10/16
 * Time: 23:30
 */

namespace GraphicObjectTemplating\Objects\OSContainer;

use GraphicObjectTemplating\Objects\OSContainer;

/**
 * Class OSDialog
 * @package GraphicObjectTemplating\Objects\OSContainer
 *
 * objet de dialogue sur base jQuery Colorbox
 */
class OSDialog extends OSContainer
{
    public function __construct($id) {
        parent::__construct($id, "oobject/oscontainer/osdialog/osdialog.config.phtml");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }
}