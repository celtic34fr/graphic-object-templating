<?php
return array(
    "object"        => "odselect",
    "typeObj"       => "odcontained",
    "template"      => "odselect.twig",
    "options"       => array(),
    "multiple"      => false,
    "select2"       => false,
    "placeholder"   => "",
    "label"         => "",
    "event"      => array(),
    "paramsSelect2" => array(
        "allowClear"    => false,
        "searchBox"     => true,
        "langue"        => 'fr',
    ),
    "resources" => array(
        "css"           => array(
            'select2.css' => "css/select2.css",
            'select2-bootstrap.css' => "css/select2-bootstrap.css",
        ),
        "js"            => array( 'select2.js' => "js/select2.js" ),
    )
);
