<?php

return array(
    "object"        => "osdialog",
    "typeObj"       => "oscontainer",
    "template"      => "osdialog.twig",
    "accessible"    => true,
    "bind"          => true,
    "class"         => "",
    "clickoff"      => true,
    "effect"        => "",
    "inner"         => "modality-inner",
    "keyboard"      => true,
    "open"          => false,
    "onClose"       => "",
    "onOpen"        => "",
    "outer"         => "modality-outer",
    "resources"     => array(
        "css" => array( 'modality.css' => "css/modality.css" ),
        "js"  => array( 'modality.jquery.js' => "js/modality.jquery.js",
                        'modality.js' => "js/modality.js" )
    )
);