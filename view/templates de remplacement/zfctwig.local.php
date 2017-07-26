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
            'debug'           => 'Twig_Extension_Debug',
        ),
    ),
    'gotParameters' => array(
        'sessionParms' => array(
            'remember_me_seconds' => 3600, // 2 heures de temps de vie (rememberMe)
            'gc_maxlifetime' => 14400,     // 4 heures de temps de vie max
            'gc_divisor' => 1,
            'use_cookies' => true,
            'cookie_httponly' => true,
        )
    )
);

//            'array'           => 'Twig_Extensions_Extension_Array',
//            'i18n'            => 'Twig_Extensions_Extension_I18n',
//            'intl'            => 'Twig_Extensions_Extension_Intl',
//            'text'            => 'Twig_Extensions_Extension_Text',
//            'date'            => 'Twig_Extensions_Extension_Date',
