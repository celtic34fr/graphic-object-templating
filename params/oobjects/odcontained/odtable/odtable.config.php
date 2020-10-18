<?php

use GraphicObjectTemplating\OObjects\ODContained\ODTable;
use GraphicObjectTemplating\OObjects\OSContainer\OSDiv;

return [
    'object'        => 'odtable',
    'typeObj'       => 'odcontained',
    'template'      => 'odtable',

    'colsHead'      => [],
    'datas'         => [],
    'events'        => [],
    'styles'        => [],

    'title'         => null,
    'titlePos'      => ODTable::TABLETITLEPOS_BOTTOM_CENTER,
    'titleStyle'    => null,

    'btnsActions'       => new OSDiv('btnsActions'),
    'btnsActionsPos'    => ODTable::TABLEBTNSACTIONS_POSITION_FIN,
    'btnsActionsHidden' => [],
];