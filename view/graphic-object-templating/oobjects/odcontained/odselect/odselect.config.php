<?php
return [
    "object"        => "odselect",
    "typeObj"       => "odcontained",
    "template"      => "odselect.twig",
    "options"       => [],
    "multiple"      => false,
    "select2"       => false,
    "placeholder"   => "",
    "label"         => "",
    "event"         => [],
    "paramsSelect2" => [
        "allowClear"    => false,
        "searchBox"     => true,
        "langue"        => 'fr',
    ],
    "resources" => [
        "css"           => [
            'select2.css'           => "css/select2.css",
            'select2-bootstrap.css' => "css/select2-bootstrap.css",
        ],
        "js"            => [
            'select2.js' => "js/select2.js",
            'odselect.js' => "js/odselect.js",
        ],
    ],
];
