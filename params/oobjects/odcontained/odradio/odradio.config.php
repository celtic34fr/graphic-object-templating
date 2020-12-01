<?php

use GraphicObjectTemplating\OObjects\ODContained\ODRadio;

return [
    'object'            => 'odradio',
    'typeObj'           => 'odcontained',
    'template'          => 'odradio.twig',

    'label'             => null,
    'options'           => [],
    'forme'             => ODRadio::RADIOFORM_HORIZONTAL,
    'hMargin'           => '0',
    'vMargin'           => '0',
    'place'             => 'left',
    'event'             => [],
    'labelWidthBT'      => null,
    'inputWidthBT'      => null,
    'checkLabelWidthBT' => null,
    'checkInputWidthBT' => null,
    'placement'         => ODRadio::RADIOPLACEMENT_LEFT,
];