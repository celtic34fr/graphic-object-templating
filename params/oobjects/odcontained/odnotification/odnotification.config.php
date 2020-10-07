<?php

use GraphicObjectTemplating\OObjects\ODContained\ODNotification;
use GraphicObjectTemplating\OObjects\OObject;

return [
    'object'        => 'odnotification',
    'typeObj'       => 'odcontained',
    'template'      => 'odnotification',

    'type'              => ODNotification::NOTIFICATIONTYPE_INFO,
    'title'             => null,
    'body'              => null,
    'size'              => ODNotification::NOTIFICATIONSIZE_NORMAL,
    'action'            => ODNotification::NOTIFICATIONACTION_INIT,
    'sound'             => OObject::BOOLEAN_TRUE,
    'soundExt'          => '.ogg',
    'soundPath'         => 'graphicobjecttemplating/objects/odcontained/odnotification/sounds/',
    'delay'             => 3000, // en millisecondes
    'delayIndicator'    => OObject::BOOLEAN_TRUE,
    'position'          => ODNotification::NOTIFICATIONPOSITION_BR,
    'showAfterPrevious' => OObject::BOOLEAN_FALSE,
    'delayMessage'      => 2000,
    'showClass'         => ODNotification::NOTIFICATIONSHOW_ZOOMIN,
    'hideClass'         => ODNotification::NOTIFICATIONHIDE_ZOOMOUT,
    'icon'              => OObject::BOOLEAN_TRUE,
    'iconSource'        => ODNotification::NOTIFICATIONICON_BOOTSTRAP,
    'width'             => 600,
    'height'            => 'auto',
    'closable'          => OObject::BOOLEAN_TRUE,
    'closeOnClick'      => OObject::BOOLEAN_TRUE,
];