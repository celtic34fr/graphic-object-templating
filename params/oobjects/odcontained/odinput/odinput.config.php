<?php

use GraphicObjectTemplating\OObjects\ODContained\ODInput;

return [
    'object'        => 'odinput',
    'typeObj'       => 'odcontained',
    'template'      => 'odinput',

    'type'          => ODInput::INPUTTYPE_TEXT,
    'size'          => null,
    'minLength'     => null,
    'maxLength'     => null,
    'label'         => null,
    'placeholder'   => null,
    'labelWidthBT'  => null,
    'inputWidthBT'  => null,
    'autoFocus'     => false,
    'mask'          => null,
    'valMin'        => null,
    'valMax'        => null,
    'reveal_pwd'    => false,
];