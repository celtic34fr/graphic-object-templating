<?php
return [
    'object'        => 'osdialog',
    'typeObj'       => 'oscontainer',
    'template'      => 'osdialog.twig',
    'btnClose'      => true,
    'widthDialog'   => '100%',
    'minHeight'     => '200px',
    'bgColor'       => '#FFF',
    'fgColor'       => '#000',

    "resources"     => [
        "css" => [ 'osdialog.css' => "css/osdialog.css", ],
        "js"  => [ 'osdialog.js' => "js/osdialog.js",]
    ],
];
?>