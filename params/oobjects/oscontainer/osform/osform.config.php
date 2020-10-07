<?php

use GraphicObjectTemplating\OObjects\OSContainer\OSDiv;
use GraphicObjectTemplating\OObjects\OSContainer\OSForm;

$btnsControls = new OSDiv('btnsControls');
$btnsControls->classes = ' formControls row ';
$btnsControls->widthBT = 12;

return [
    'object'            => 'osform',
    'typeObj'           => 'oscontainer',
    'template'          => 'osform',

    'title'             => null,
    'origine'           => [],
    'hidden'            => [],
    'required'          => [],
    'submitEnter'       => false,
    'btnsControls'      => $btnsControls,
    'btnsDisplay'       => OSForm::DISP_BTN_HORIZONTAL,
    'btnsWidthBT'       => null,
    'widthBTbody'       => null,
    'widthBTctrls'      => null,
];