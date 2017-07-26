<?php

return array(
    "object"            => "odnotification",
    "typeObj"           => "odcontained",
    "template"          => "odnotification.twig",
    "type"              => "info",
    "title"             => "",
    "body"              => "",
    "size"              => "normal",
    "action"            => "init",
    "sound"             => true,
    "soundPath"         => "graphicobjecttemplating/objects/odcontained/odnotification/sounds/",
    "delay"             => 3000, // en millisecondes
    "position"          => 'bottom right',
    "showAfterPrevious" => false,
    "delayMessage"      => 2000,

    "resources" => array(
        "css" => array( 'lobibox.css' => "css/lobibox.css", ),
        "js"  => array( 'lobibox.js' => "js/lobibox.js", )
    )
);
