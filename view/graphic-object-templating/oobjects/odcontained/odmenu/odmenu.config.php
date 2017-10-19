<?php
return [
    'object'     => 'odmenu',
    'typeObj'    => 'odcontained',
    'template'   => 'odmenu.twig',

    'dataTree'       => [],
    'dataPath'       => [],
    'activMenu'      => '',
    'title'          => '',
    'labelTitle'     => true,
    'iconTitle'      => false,

    'resources' => [
        "css" => array( 'odmenu.css' => "css/odmenu.css", ),
        "js"  => array( 'odmenu.js' => "js/odmenu.js", )
    ]
];