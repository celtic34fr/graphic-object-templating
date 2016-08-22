<?php
return array(
    // ... ZfcTwig Configuration
    'zfctwig' => array(
        'environment_options' => [
//            'cache' => 'data/cache/twig',
            'cache' => false,
            'debug' => true
        ],
        'extensions' => array(
            'layoutextension' => 'GraphicObjectTemplating\Twig\Extension\LayoutExtension',
            'colorconverter'  => 'GraphicObjectTemplating\Twig\Extension\ColorConverterTwigExtension',
            'debug'           => 'Twig_Extension_Debug',
        ),
    ),

);

//            'array'           => 'Twig_Extensions_Extension_Array',
//            'i18n'            => 'Twig_Extensions_Extension_I18n',
//            'intl'            => 'Twig_Extensions_Extension_Intl',
//            'text'            => 'Twig_Extensions_Extension_Text',
//            'date'            => 'Twig_Extensions_Extension_Date',
