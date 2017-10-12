<?php

use GraphicObjectTemplating\Objects\OSContainer\OSDialog;

return [
    'object'        => 'osdialog',
    'typeObj'       => 'oscontainer',
    'template'      => 'osdialog.twig',
    'btnClose'      => true,
    'widthDialog'   => '100%',
    'minHeight'     => '200px',
    'bgColor'       => 'bg-'.OSDialog::COLOR_WHITE,
    'fgColor'       => 'fg-'.OSDialog::COLOR_BLACK,

    "resources"     => [
        "css" => [ 'osdialog.css' => "css/osdialog.css", ],
        "js"  => [ 'osdialog.js' => "js/osdialog.js",]
    ],
];
?>