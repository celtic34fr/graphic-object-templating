<?php

use GraphicObjectTemplating\OObjects\ODContained\ODCheckbox;

return [
    'object'        => 'odcheckbox',
    'typeObj'       => 'odcontained',
    'template'      => 'odcheckbox',

    'label'             => null,
    'options'           => [],
    'forme'             => ODCheckbox::CHECKFORME_HORIZONTAL,
    'hMargin'           => '0',
    'vMargin'           => '0',
    'eventChk'          => [],
    'labelWidthBT'      => null,
    'inputWidthBT'      => null,
    'checkLabelWidthBT' => null,
    'checkInputWidthBT' => null,
    'placement'         => ODCheckbox::CHECKPLACEMENT_LEFT,
];