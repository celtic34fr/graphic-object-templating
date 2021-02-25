<?php

use GraphicObjectTemplating\OObjects\ODContained\ODInput;

return [
    'object'            => 'odinput',
    'typeObj'           => 'odcontained',
    'template'          => 'odinput',

    'type'              => ODInput::INPUTTYPE_TEXT,
    'size'              => null,
    'min_length'        => null,
    'max_length'        => null,
    'label'             => null,
    'placeholder'       => null,
    'label_width_bt'    => null,
    'input_width_bt'    => null,
    'auto_focus'        => false,
    'mask'              => null,
    'val_min'           => null,
    'val_max'           => null,
    'reveal_pwd'        => false,
];