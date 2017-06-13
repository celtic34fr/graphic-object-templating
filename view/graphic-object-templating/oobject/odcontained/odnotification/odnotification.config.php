<?php

return array(
    "object"            => "odnotification",
    "typeObj"           => "odcontained",
    "template"          => "odnotification.twig",
    "type"              => "",
    "title"             => "",
    "body"              => "",
    "size"              => "normal",
    "action"            => "init",
    "sound"             => true,
    "soundPath"         => __DIR__ . "/../../../../../public/objets/odcontained/odnotification/sounds/",
    "delay"             => 3000, // en millisecondes
    "position"          => 'bottom right',
    "showAfterPrevious" => false,
    "delayMessage"      => 2000,

    "resources" => array(
        "css" => array( "css/lobibox.css", ),
        "js"  => array( "js/lobibox.js", )
    )
);
