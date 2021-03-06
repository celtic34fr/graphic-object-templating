<?php

namespace GraphicObjectTemplating\OObjects\ODContained;

use BadFunctionCallException;
use Exception;
use GraphicObjectTemplating\OObjects\ODContained;
use GraphicObjectTemplating\OObjects\OSContainer\OSDiv;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Class ODTable
 * @package GraphicObjectTemplating\OObjects\ODContained
 *
 * méthodes
 * --------
 * __construct(string $id)
 * __get(string $key)
 * getPrefix(string $key, $params, array $cols, array $datas, array $events, array $styles)
 * getColumnDatas(int $params): array
 * getBtnAction(string $idBtn)
 * __isset(string $key): bool
 * issetPrefix(string $key, $params, array $cols, array $datas, array $events, array $styles): bool
 * issetBtnAction(string $idBtn): bool
 * __set(string $key, $val)
 * setPrefix(string $key, $params, array $cols, array $datas, array $events, array $styles, $val): array
 * setColumnDatas(int $params, array $val): array
 * validate_colsWithBT($val, $cols)
 * validate_colsWith($val, $cols)
 * setBtnAction(ODButton $btn)
 * addBtnAction(ODButton $btn)
 * clearBtnsActions()
 * rmBtnAction($btnAction)
 * showCol(int $col = null)
 * hideCol(int $col = null)
 * hideBtnsActions(int $noLine)
 * showBtnsActions(int $noLine)
 * hideBtnAction($btnAction, int $noLine)
 * showBtnAction($btnAction, int $noLine)
 */
class ODTable extends ODContained
{
    const TABLE_PREFIX_HEADER = 'h';  // entête accès à l'attribut cols
    const TABLE_PREFIX_COLUMN = 'c';  // accès à und colonne de l'attribut $datas
    const TABLE_PREFIX_LINE = 'l';  // accès à une ligne de l'attribut $datas
    const TABLE_PREFIX_INTERSECT = 'i';  // accès l'intersection ligne, colonne de l'attribut $datas
    const TABLE_PREFIX_EVT_INTERSECT = 'a';  // gestion évènement sur une intersection de l'attribut $datas
    const TABLE_PREFIX_EVT_LINE = 'e';  // gestion évènement sur une ligne de l'attribut $datas
    const TABLE_PREFIX_EVT_COLUMN = 'f';  // gestion évènement sur une colonne de l'attribut $datas
    const TABLE_PREFIX_STYLE_INTERSECT = 's';  // gestion de style sur une intersection de l'attribut $datas
    const TABLE_PREFIX_STYLE_LINE = 'r';  // gestion de style sur une ligne de l'attribut $datas
    const TABLE_PREFIX_STYLE_COLUMN = 'p';  // gestion de style sur une colonne de l'attributy $datas

    const TABLETITLEPOS_TOP_LEFT = "top_left";
    const TABLETITLEPOS_TOP_CENTER = "top_center";
    const TABLETITLEPOS_TOP_RIGHT = "opt_right";
    const TABLETITLEPOS_BOTTOM_LEFT = "bottom_left";
    const TABLETITLEPOS_BOTTOM_CENTER = "bottom_center";
    const TABLETITLEPOS_BOTTOM_RIGHT = "bottom_right";

    const TABLEBTNSACTIONS_POSITION_DEBUT = 1;
    const TABLEBTNSACTIONS_POSITION_FIN = PHP_FLOAT_MAX;

    const EVENT_CLICK = 'click';
    const EVENT_HOVER = 'hover';

    const ERR_TABLEAU_EVENT_SANS_EVT_MSG = "Tableau évènement sans code évènement";
    const ERR_TABLEAU_EVENT_BAD_BUILD_MSG = "Tableau évènement mal construit";
    const PREFIX_MSG = "ODButton ";

    const ERR_PREFIX_EVT_COLUMN = "Numéro de colonne ";
    const ERR_PREFIX_EVT_LINE = "Numéro de Ligne ";
    const ERR_PREFIX_EVT_INTERSECT = "Coordonnées de cellule  ";

    protected static $odtable_attributes = ["colsHead", "datas","events", "styles", "title", "titlePos", "titleStyle",
        "btnsActions", "btnsActionsPos", "btnsActionsHidden"];

    const OMMIT_KEYS_PREFIX = ['widthBT', 'typeObj', 'object', 'children', 'name', 'display', 'lastAccess', 'state', 'classes', 'autoCenter',
        'acPx', 'acPy', 'className', 'template', 'valeur', 'form', 'default', 'event', 'title', 'titlePos', 'titleStyle', 'btnsActions',
        'btnsActionsPos', 'btnsActionsHidden', 'infoBulle',
    ];


    /**
     * ODTable constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $path = __DIR__ . '/../../../params/oobjects/odcontained/odtable/odtable.config.php';
        $properties = require $path;
        parent::__construct($id, $properties);

        $properties = $this->object_contructor($id, $properties);
        if ((int)$properties['widthBT'] === 0) {
            $properties['widthBT'] = $this->validate_widthBT(12);
        }
        $btnsActions = $properties['btnsActions'];
        $btnsActions->id = $this->id . 'BtnsActions';
        $properties['btnsActions'] = $btnsActions;
        $this->properties = $properties;
    }

    /**
     * @param string $key
     * @return mixed|void|null
     */
    public function __get(string $key)
    {
        $properties = $this->properties;
        $cols = $properties['colsHead'];
        $datas = $properties['datas'];
        $events = $properties['events'];
        $styles = $properties['styles'];
        $btnsActions = $properties['btnsActions'];
        switch ($key) {
            case 'colsHead':
                $val = $cols;
                break;
            case 'nbColsHead':
                $val = count($cols);
                break;
            case 'colsWidth':
            case 'colsWidthBT':
                throw new BadFunctionCallException("Attribut $key accessible en écriture seleument");
                break;
            case 'line':
            case 'lines':
                throw new BadFunctionCallException("Attribut line / Lines accessible en écriture seleument");
                break;
            case 'datas':
                $val = $properties['datas'];
                break;
            case 'nbLinesData':
                $val = count($properties['datas']);
                break;
            case 'styles':
                $val = $styles;
                break;
            case 'events':
                $val = $events;
                break;
            case 'btnsActions':
                /** @var OSDiv $btnsActions */
                $val = $btnsActions->children;
                break;
            default:
                $prefix = $key[0];
                $params = (int)substr($key, 1);
                if (!in_array($key, self::OMMIT_KEYS_PREFIX)) {
                    if (!in_array($prefix, $this->getConstantsGroup("TABLE_PREFIX_"), true) || (!is_numeric($params) && $prefix !== self::TABLE_PREFIX_INTERSECT)) {
                        throw new InvalidArgumentException("Attribut $key incorrect");
                    }
                    return $this->getPrefix($prefix, $params, $cols, $datas, $events, $styles);
                }
                return parent::__get($key);
        }
        return $val;
    }

    /**
     * @param string $key
     * @param $params
     * @param array $cols
     * @param array $datas
     * @param array $events
     * @param array $styles
     * @return array|false|mixed|null
     * @throws Exception
     */
    public function getPrefix(string $key, $params, array $cols, array $datas, array $events, array $styles)
    {
        switch ($key) {
            case self::TABLE_PREFIX_HEADER:
                if ($params === 0 || $params > count($cols)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = $cols[$params];
                break;
            case self::TABLE_PREFIX_COLUMN:
                if ($params === 0 || $params > count($cols)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = $this->getColumnDatas($params);
                break;
            case self::TABLE_PREFIX_LINE:
                if ($params === 0 || $params > count($datas)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = $datas[$params];
                break;
            case self::TABLE_PREFIX_INTERSECT:
                $params = explode('-', $params);
                $ctrl = [count($cols), count($datas)];
                if (count($params) !== 2) {
                    throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                }
                foreach ($params as $idx => $param) {
                    if (!is_numeric($param) || $param === 0 || $params > $ctrl[$idx]) {
                        throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                    }
                }
                $val = $datas[$params[0]][$params[1]];
                break;
            case self::TABLE_PREFIX_EVT_COLUMN:
                if ($params === 0 || $params > count($cols)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = $events[0][$params];
                break;
            case self::TABLE_PREFIX_EVT_LINE:
                if ($params === 0 || $params > count($datas)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = $events[$params][0];
                break;
            case self::TABLE_PREFIX_EVT_INTERSECT:
                $params = explode('-', $params);
                $ctrl = [count($cols), count($datas)];
                if (count($params) !== 2) {
                    throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                }
                foreach ($params as $idx => $param) {
                    if (!is_numeric($param) || $param === 0 || $params > $ctrl[$idx]) {
                        throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                    }
                }
                $val = $events[$params[0]][$params[1]];
                break;
            case self::TABLE_PREFIX_STYLE_COLUMN:
                if ($params === 0 || $params > count($cols)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = $styles[0][$params];
                break;
            case self::TABLE_PREFIX_STYLE_LINE:
                if ($params === 0 || $params > count($datas)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = $styles[$params][0];
                break;
            case self::TABLE_PREFIX_STYLE_INTERSECT:
                $params = explode('-', $params);
                $ctrl = [count($cols), count($datas)];
                if (count($params) !== 2) {
                    throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                }
                foreach ($params as $idx => $param) {
                    if (!is_numeric($param) || $param === 0 || $params > $ctrl[$idx]) {
                        throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                    }
                }
                $val = $styles[$params[0]][$params[1]];
                break;
            default:
                return parent::__get($key);
        }
        return $val;
    }

    /**
     * @param int $params
     * @return array
     */
    private function getColumnDatas(int $params): array
    {
        $properties = $this->properties;
        $datas = $properties['datas'];
        $colDatas = [];

        foreach ($datas as $idx => $data) {
            $colDatas[$idx] = $datas[$idx][$params];
        }

        return $colDatas;
    }

    /**
     * @param string $idBtn
     * @return false|mixed
     */
    public function getBtnAction(string $idBtn)
    {
        $children = $this->btnsActions->children;
        return array_key_exists($idBtn, $children) ? $children[$idBtn] : false;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        $properties = $this->properties;
        $cols = $properties['colsHead'];
        $datas = $properties['datas'];
        $events = $properties['events'];
        $styles = $properties['styles'];
        switch ($key) {
            case 'nbColsHead':
            case 'nbLinesData':
                throw new BadFunctionCallException("Attribut $key accessible en lecture seulement");
            case 'line':
            case 'lines':
            case 'colsWidth':
            case 'colsWidthBT':
                throw new BadFunctionCallException("Attribut $key accessible en écriture seulement");
            case 'colsHead':
                $val = (count($cols) > 0);
                break;
            case 'datas':
                $val = (count($datas) > 0);
                break;
            case 'styles':
                $val = (count($styles) > 0);
                break;
            case 'events':
                $val = (count($events) > 0);
                break;
            default:
                $prefix = $key[0];
                $params = (int)substr($key, 1);
                if (!in_array($key, self::OMMIT_KEYS_PREFIX)) {
                    if (!in_array($prefix, $this->getConstantsGroup("TABLE_PREFIX_"), true) || (!is_numeric($params) && $prefix !== self::TABLE_PREFIX_INTERSECT)) {
                        throw new InvalidArgumentException("Attribut $key incorrect");
                    }
                    return $this->issetPrefix($prefix, $params, $cols, $datas, $events, $styles);
                }
                return parent::__isset($key);
        }
        return $val;
    }

    /**
     * @param string $key
     * @param $params
     * @param array $cols
     * @param array $datas
     * @param array $events
     * @param array $styles
     * @return bool
     * @throws Exception
     */
    public function issetPrefix(string $key, $params, array $cols, array $datas, array $events, array $styles): bool
    {
        switch ($key) {
            case self::TABLE_PREFIX_HEADER:
                if ($params === 0 || $params > count($cols)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = (!empty($cols[$params]));
                break;
            case self::TABLE_PREFIX_COLUMN:
                if ($params === 0 || $params > count($cols)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $datasCol = $this->getColumnDatas($params);
                $flag = false;
                foreach ($datasCol as $dataCol) {
                    $flag = $flag || !empty($dataCol);
                }
                $val = $flag;
                break;
            case self::TABLE_PREFIX_LINE:
                if ($params === 0 || $params > count($datas)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = array_key_exists($params, $datas);
                break;
            case self::TABLE_PREFIX_INTERSECT:
                $params = explode('-', $params);
                $ctrl = [count($cols), count($datas)];
                if (count($params) !== 2) {
                    throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                }
                foreach ($params as $idx => $param) {
                    if (!is_numeric($param) || $param === 0 || $params > $ctrl[$idx]) {
                        throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                    }
                }
                $val = !empty($datas[$params[0]][$params[1]]);
                break;
            case self::TABLE_PREFIX_EVT_COLUMN:
                if ($params === 0 || $params > count($cols)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = !empty($events[0][$params]);
                break;
            case self::TABLE_PREFIX_EVT_LINE:
                if ($params === 0 || $params > count($datas)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = !empty($events[$params][0]);
                break;
            case self::TABLE_PREFIX_EVT_INTERSECT:
                $params = explode('-', $params);
                $ctrl = [count($cols), count($datas)];
                if (count($params) !== 2) {
                    throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                }
                foreach ($params as $idx => $param) {
                    if (!is_numeric($param) || $param === 0 || $params > $ctrl[$idx]) {
                        throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                    }
                }
                $val = !empty($events[$params[0]][$params[1]]);
                break;
            case self::TABLE_PREFIX_STYLE_COLUMN:
                if ($params === 0 || $params > count($cols)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = !empty($styles[0][$params]);
                break;
            case self::TABLE_PREFIX_STYLE_LINE:
                if ($params === 0 || $params > count($datas)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                $val = !empty($styles[$params][0]);
                break;
            case self::TABLE_PREFIX_STYLE_INTERSECT:
                $params = explode('-', $params);
                $ctrl = [count($cols), count($datas)];
                if (count($params) !== 2) {
                    throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                }
                foreach ($params as $idx => $param) {
                    if (!is_numeric($param) || $param === 0 || $params > $ctrl[$idx]) {
                        throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                    }
                }
                $val = !empty($styles[$params[0]][$params[1]]);
                break;
            default:
                return parent::__isset($key);
        }
        return $val;
    }

    /**
     * @param string $idBtn
     * @return bool
     */
    public function issetBtnAction(string $idBtn): bool
    {
        $children = $this->btnsActions->children;
        return array_key_exists($idBtn, $children);
    }

    /**
     * @param string $key
     * @param $val
     * @return mixed|void|null
     * @throws Exception
     */
    public function __set(string $key, $val)
    {
        $cols = $this->properties['colsHead'];
        $datas = $this->properties['datas'];
        $events = $this->properties['events'];
        $styles = $this->properties['styles'];
        switch ($key) {
            case 'nbColsHead':
            case 'nbLinesData':
                throw new BadFunctionCallException("Attribut $key accessinle en lecture seulement");
            case 'colsHead':
                if (!is_array($val)) {
                    throw new InvalidArgumentException("L'attribut colsHead n'accepte que des tableaux unidimensionnels");
                }
                $colsTab = [];
                foreach ($val as $colName) {
                    $item = [];
                    $item['libel'] = $colName;
                    $item['view'] = true;
                    $colsTab[count($colsTab) + 1] = $item;
                }
                $val = $colsTab;
                break;
            case 'colsWidth':
                $val = $this->validate_colsWith($val, $cols);
                $key = 'colsHead';
                break;
            case 'colsWidthBT':
                $val = $this->validate_colsWithBT($val, $cols);
                $key = 'colsHead';
                break;
            case 'line':
                if (!is_array($val)) {
                    throw new InvalidArgumentException("L'attribut line n'accepte que des tableaux unidimensionnels");
                }
                if (count($cols) !== count($val)) {
                    throw new UnexpectedValueException("Valeur donnée : nombre de colones incorrectes");
                }
                $datas = $this->properties['datas'];
                $datas[] = $val;
                $key = 'datas';
                $val = $datas;
                break;
            case 'lines':
                if (!is_array($val)) {
                    throw new InvalidArgumentException("L'attribut line n'accepte que des tableaux bidimensionnels");
                }
                foreach ($val as $cols) {
                    if (count($cols) !== count($val)) {
                        throw new InvalidArgumentException("Valeur donnée (tableau) : nombre de colones incorrectes");
                    }
                }
                $datas = $this->properties['datas'];
                $datas = array_merge($val, $datas);
                $key = 'datas';
                $val = $datas;
                break;
            case 'datas':
                if (!is_array($val)) {
                    throw new UnexpectedValueException("L'attribut datas n'accepte que des tableaux bidimensionnels");
                }
                foreach ($val as $cols) {
                    if (count($cols) !== count($val)) {
                        throw new InvalidArgumentException("Valeur donnée (tableau) : nombre de colones incorrectes");
                    }
                }
                break;
            case 'styles':
            case 'events':
                if (!is_array($val)) {
                    throw new UnexpectedValueException("L'attribut $key n'accepte que des tableaux bidimensionnels");
                }
                if (max(array_keys($val)) > count($datas)) {
                    throw new InvalidArgumentException("Indice de tableau fourni supérieur au nombre de lignes du tableau");
                }
                if (min(array_keys($val)) <= 0) {
                    throw new UnexpectedValueException("Indice de tableau fourni commance en dessous de 1, incompatible");
                }
                $flag = true;
                foreach ($val as $line) {
                    $flag = $flag && (max(array_keys($line)) > count($cols));
                }
                if (!$flag) {
                    throw new InvalidArgumentException("Indice de tableau second niveau fourni supérieur au nombre de colonnes du tableau");
                }
                break;
            case 'title':
            case 'titleStyle':
                $val = (string)$val;
                break;
            case 'titlePos':
                $val = $this->validate_By_Constants($val, "TABLETITLEPOS_", self::TABLETITLEPOS_BOTTOM_CENTER);
                break;
            case 'btnsActions':
                if (!is_array($val)) {
                    throw new UnexpectedValueException("L'attribut $key n'accepte que des tableaux monodimensionnels");
                }
                /** @var OSDiv $btnsActions */
                $btnsActions = $this->btnsActions;
                foreach ($val as $cle => $item) {
                    if (!($item instanceof ODButton)) {
                        throw new UnexpectedValueException("L'attribut $key n'accepte que des tableaux d'objet ODButton");
                    }
                    if ($cle != $item->id) {
                        throw new InvalidArgumentException("Objet ODButton " . $item->id . " référencé par clé $cle incohérente");
                    }
                    $btnsActions->addChild($item);
                }
                break;
            case 'btnsActionsPos':
                if (strtolower(substr($val, -2)) === 'th' && is_numeric(substr($val, 0, -2))) {
                    $val = strtolower($val);
                } else {
                    $val = $this->validate_By_Constants($val, "TABLEBTNSACTIONS_POSITION_", self::TABLEBTNSACTIONS_POSITION_FIN);
                }
                break;
            default:
                $prefix = $key[0];
                $params = (int)substr($key, 1);
                if (!in_array($key, ['widthBT', 'typeObj', 'object', 'children'])) {
                    if (!in_array($prefix, $this->getConstantsGroup("TABLE_PREFIX_"), true) || (!is_numeric($params) && $prefix !== self::TABLE_PREFIX_INTERSECT)) {
                        throw new InvalidArgumentException("Attribut $key incorrect");
                    }
                    if (array_key_exists($prefix, $this->getConstantsGroup("TABLE_PREFIX_"))) {
                        list($key, $val) = $this->setPrefix($prefix, $params, $cols, $datas, $events, $styles, $val);
                    } else {
                        return parent::__set($key, $val);
                    }
                }
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @param string $key
     * @param $params
     * @param array $cols
     * @param array $datas
     * @param array $events
     * @param array $styles
     * @param $val
     * @return array
     * @throws Exception
     */
    public function setPrefix(string $key, $params, array $cols, array $datas, array $events, array $styles, $val): array
    {
        switch ($key) {
            case self::TABLE_PREFIX_HEADER:
                if ($params === 0 || $params > count($cols)) {
                    throw new InvalidArgumentException("Numéro de colonne $params incompatible");
                }
                $cols[$params]['libel'] = $val;
                $val = $cols;
                $key = 'colsHead';
                break;
            case self::TABLE_PREFIX_COLUMN:
                if ($params === 0 || $params > count($cols)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                if (!is_array($val)) {
                    throw new UnexpectedValueException("L'attribut $key n'accepte que des tableaux unidimensionnels");
                }
                if (count($val) !== count($datas)) {
                    throw new InvalidArgumentException("Nombre de lignes à modifier incorrect : " . count($val) . 'au lieu de ' . count($datas));
                }
                $val = $this->setColumnDatas($params, $val);
                $key = 'datas';
                break;
            case self::TABLE_PREFIX_LINE:
                if ($params === 0 || $params > count($datas)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                if (count($cols) !== count($val)) {
                    throw new InvalidArgumentException("Valeur donnée : nombre de colones incorrectes");
                }
                $datas[$params] = $val;
                $val = $datas;
                $key = 'datas';
                break;
            case self::TABLE_PREFIX_INTERSECT:
                $params = explode('-', $params);
                $ctrl = [count($cols), count($datas)];
                if (count($params) !== 2) {
                    throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                }
                foreach ($params as $idx => $param) {
                    if (!is_numeric($param) || $param === 0 || $params > $ctrl[$idx]) {
                        throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                    }
                }
                $datas[$params[0]][$params[1]] = $val;
                $val = $datas;
                $key = 'datas';
                break;
            case self::TABLE_PREFIX_EVT_COLUMN:
            case self::TABLE_PREFIX_EVT_LINE:
            case self::TABLE_PREFIX_EVT_INTERSECT:
                switch ($key) {
                    case self::TABLE_PREFIX_EVT_COLUMN:
                        $prefix = self::ERR_PREFIX_EVT_COLUMN;
                        if ($params === 0 || $params > count($cols)) {
                            throw new UnexpectedValueException($prefix . "$params incompatible");
                        }
                        break;
                    case self::TABLE_PREFIX_EVT_LINE:
                        $prefix = self::ERR_PREFIX_EVT_LINE;
                        if ($params === 0 || $params > count($datas)) {
                            throw new UnexpectedValueException($prefix . "$params incompatible");
                        }
                        break;
                    case self::TABLE_PREFIX_EVT_INTERSECT:
                        $prefix = self::ERR_PREFIX_EVT_INTERSECT;
                        $params = explode('-', $params);
                        $ctrl = [count($cols), count($datas)];
                        if (count($params) !== 2) {
                            throw new InvalidArgumentException($prefix . "$params incompatible");
                        }
                        foreach ($params as $idx => $param) {
                            if (!is_numeric($param) || $param === 0 || $params > $ctrl[$idx]) {
                                throw new UnexpectedValueException($prefix . "$params incompatible");
                            }
                        }
                        break;
                    default:
                        throw new UnexpectedValueException('Unexpected value');
                }
                if (!is_array($val)) {
                    throw new UnexpectedValueException($prefix . ": L'attribut $key n'accepte que des tableaux unidimensionnels");
                }
                if (!array_key_exists('evt', $val)) {
                    throw new InvalidArgumentException(self::ERR_TABLEAU_EVENT_SANS_EVT_MSG);
                }
                $evt = $val['evt'];
                unset($val['evt']);
                if (!in_array($evt, $this->getConstantsGroup('EVENT_'))) {
                    throw new UnexpectedValueException("Evènement $evt non géré");
                }
                $val = $this->validate_event_parms($val);
                if ($val === false) {
                    throw new InvalidArgumentException(self::ERR_TABLEAU_EVENT_BAD_BUILD_MSG);
                }
                if (!array_key_exists($params, $events)) {
                    $events[$params] = [];
                }
                if (!array_key_exists(0, $events[$params])) {
                    $events[$params][0] = [];
                }
                if (!array_key_exists($evt, $events[$params][0])) {
                    $events[$params][0][$evt] = [];
                }
                $events[$params][0][$evt] = $val;
                $key = 'events';
                $val = $events;
                break;
            case self::TABLE_PREFIX_STYLE_COLUMN:
                if ($params === 0 || $params > count($cols)) {
                    throw new InvalidArgumentException("Numéro de colonne $params incompatible");
                }
                if (!is_string($val)) {
                    throw new UnexpectedValueException("L'attribut $key n'accepte que des chaînes de caractères");
                }
                if (!array_key_exists(0, $styles)) {
                    $styles[0] = [];
                }
                if (!array_key_exists($params, $styles[0])) {
                    $styles[0][$params] = [];
                }
                $events[0][$params] = $val;
                $key = 'styles';
                $val = $styles;
                break;
            case self::TABLE_PREFIX_STYLE_LINE:
                if ($params === 0 || $params > count($datas)) {
                    throw new UnexpectedValueException("Numéro de colonne $params incompatible");
                }
                if (!is_string($val)) {
                    throw new UnexpectedValueException("L'attribut $key n'accepte que des chaînes de caractères");
                }
                if (!array_key_exists($params, $styles)) {
                    $styles[$params] = [];
                }
                if (!array_key_exists(0, $styles[$params])) {
                    $styles[$params][0] = [];
                }
                $events[$params][0] = $val;
                $key = 'styles';
                $val = $styles;
                break;
            case self::TABLE_PREFIX_STYLE_INTERSECT:
                $params = explode('-', $params);
                $ctrl = [count($cols), count($datas)];
                if (count($params) !== 2) {
                    throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                }
                foreach ($params as $idx => $param) {
                    if (!is_numeric($param) || $param === 0 || $params > $ctrl[$idx]) {
                        throw new UnexpectedValueException("Coordonnées de cellule $params incompatible");
                    }
                }
                if (!is_string($val)) {
                    throw new UnexpectedValueException("L'attribut $key n'accepte que des chaînes de caractères");
                }
                if (!array_key_exists($params[0], $styles)) {
                    $styles[$params[0]] = [];
                }
                if (!array_key_exists($params[1], $styles[$params[0]])) {
                    $events[$params[0]][$params[1]] = [];
                }
                $events[$params[0]][$params[1]] = $val;
                $key = 'styles';
                $val = $styles;
                break;
            default:
                throw new InvalidArgumentException("Paramètres incorrect veuillez vérifier vos données");
        }
        return [$key, $val];
    }

    /**
     * @param int $params
     * @param array $val
     * @return array
     */
    private function setColumnDatas(int $params, array $val): array
    {
        $properties = $this->properties;
        $datas = $properties['datas'];
        foreach ($val as $idx => $value) {
            if ($idx === 0) {
                throw new InvalidArgumentException("Indice de tableau commençant à 0 incompaticle");
            }
            $datas[$idx][$params] = $value;
        }
        return $datas;
    }

    /**
     * @param $val
     * @param $cols
     * @return mixed
     * @throws Exception
     */
    private function validate_colsWithBT($val, $cols)
    {
        if (!is_array($val)) {
            throw new UnexpectedValueException("L'attribut colsWidth n'accepte que des tableaux unidimensionnels");
        }

        $nbCols = count($cols);
        $nbVals = count($val);
        if ($nbVals === 0 || $nbVals !== $nbCols) {
            throw new UnexpectedValueException("Le tableau accepté par l'attribut colsWidth doit avoir le même nombre de colonne que l'objet actuel");
        }

        foreach ($val as $key => $width) {
            $width = $this->validate_widthBT($width);
            $cols[$key + 1]['widthBT'] = $width;
        }
        return $cols;

    }

    /**
     * @param $val
     * @param $cols
     * @return mixed
     * @throws Exception
     */
    private function validate_colsWith($val, $cols)
    {
        if (!is_array($val)) {
            throw new UnexpectedValueException("L'attribut colsWidth n'accepte que des tableaux unidimensionnels");
        }

        $nbCols = count($cols);
        $nbVals = count($val);
        if ($nbVals === 0 || $nbVals !== $nbCols) {
            throw new InvalidArgumentException("Le tableau accepté par l'attribut colsWidth doit avoir le même nombre de colonne que l'objet actuel");
        }

        foreach ($val as $key => $width) {
            $cols[$key + 1]['width'] = $width;
        }
        return $cols;
    }

    /**
     * @param ODButton $btn
     * @throws Exception
     */
    public function setBtnAction(ODButton $btn)
    {
        $children = $this->btnsActions->children;
        if (!array_key_exists($btn->id, $children)) {
            throw new UnexpectedValueException(self::PREFIX_MSG . $btn->id . " non présent dans tableau des boutons action en affectation, erreur");
        }
        $children = $this->btnsActions->children;
        $children[$btn->id] = $btn;
        $this->btnsActions->children = $children;
    }

    /**
     * @param ODButton $btn
     * @throws Exception
     */
    public function addBtnAction(ODButton $btn)
    {
        /** @var OSDiv $btnsActions */
        $btnsActions = $this->btnsActions;
        $children = $this->btnsActions->children;
        if (array_key_exists($btn->id, $children)) {
            throw new UnexpectedValueException(self::PREFIX_MSG . $btn->id . " dèjà présent dans tableau des boutons action en ajout, erreur");
        }
        $children[$btn->id] = $btn;
        $btnsActions->addChild($btn);
        $this->btnsActions = $btnsActions;
    }

    /**
     *
     */
    public function clearBtnsActions()
    {
        $this->btnsActions = new OSDiv($this->id . 'BtnsAcations');
    }

    /**
     * @param ODButton $btn
     * @throws Exception
     */
    public function rmBtnAction($btnAction)
    {
        if ($btnAction instanceof ODButton) {
            $btnAction = $btnAction->id;
        }
        if (!is_string($btnAction)) {
            throw new InvalidArgumentException("demande de suppression impossible, passer soit un objet OObject soit un identifiant");
        }

        if (!array_key_exists($btnAction, $this->btnsActions)) {
            throw new UnexpectedValueException(self::PREFIX_MSG . $btnAction . " non présent dans tableau des boutons action en suppression, erreur");
        }
        /** @var OSDiv $btnsActions */
        $btnsActions = $this->btnsActions;
        $this->btnsActions = $btnsActions->rmChild($btnAction);
    }

    /**
     * @param int|null $col
     * @return ODTable
     * @throws Exception
     */
    public function showCol(int $col = null)
    {
        $properties = $this->properties;
        $colsHead = $properties['colsHead'];
        $colsHeadSize = count($colsHead);
        if ($col === null || $col < 1 || $col > $colsHeadSize) {
            throw new InvalidArgumentException("L'indice de colonne doit être numérique et entre 1 et $colsHeadSize");
        }

        $colsHead[$col]['view'] = true;
        $properties['colsHead'] = $colsHead;
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param int|null $col
     * @return ODTable
     * @throws Exception
     */
    public function hideCol(int $col = null)
    {
        $properties = $this->properties;
        $colsHead = $properties['colsHead'];
        $colsHeadSize = count($colsHead);
        if ($col === null || $col < 1 || $col > $colsHeadSize) {
            throw new InvalidArgumentException("L'indice de colonne doit être numérique et entre 1 et $colsHeadSize");
        }

        $colsHead[$col]['view'] = false;
        $properties['colsHead'] = $colsHead;
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param int $noLine
     * @return ODTable
     * @throws Exception
     */
    public function hideBtnsActions(int $noLine)
    {
        $datas = $this->datas;
        if (array_key_exists($noLine, $datas)) {
            throw new UnexpectedValueException("Numéro de ligne $noLine inexistant");
        }
        $btnsActions = $this->btnsActions;
        $hidden = implode("|", array_keys($btnsActions->children));
        $btnsActionsHidden = $this->btnsActionsHidden;
        $btnsActionsHidden[$noLine] = $hidden;
        $this->btnsActionsHidden = $btnsActionsHidden;
        return $this;
    }

    /**
     * @param int $noLine
     * @return ODTable
     * @throws Exception
     */
    public function showBtnsActions(int $noLine)
    {
        $datas = $this->datas;
        if (!array_key_exists($noLine, $datas)) {
            throw new UnexpectedValueException("Numéro de ligne $noLine inexistant");
        }
        $btnsActionsHidden = $this->btnsActionsHidden;
        if (array_key_exists($noLine, $btnsActionsHidden)) {
            unset($btnsActionsHidden[$noLine]);
        }
        $this->btnsActionsHidden = $btnsActionsHidden;
        return $this;
    }

    /**
     * @param $btnAction
     * @param int $noLine
     * @return ODTable
     * @throws Exception
     */
    public function hideBtnAction($btnAction, int $noLine)
    {
        if ($btnAction instanceof ODButton) {
            $btnAction = $btnAction->id;
        }
        if (!is_string($btnAction)) {
            throw new InvalidArgumentException("demande de suppression visibilité de bouton action, passer soit un objet ODButton soit un identifiant");
        }
        $datas = $this->datas;
        if (!array_key_exists($noLine, $datas)) {
            throw new InvalidArgumentException("Numéro de ligne $noLine inexistant");
        }
        $btnsActionsHidden = $this->btnsActionsHidden;
        if (array_key_exists($noLine, $btnsActionsHidden)) {
            $btnsActionsHidden[$noLine] .= '|' . $btnAction;
        } else {
            $btnsActionsHidden[$noLine] = $btnAction;
        }
        $this->btnsActionsHidden = $btnsActionsHidden;
        return $this;
    }

    /**
     * @param $btnAction
     * @param int $noLine
     * @return ODTable
     * @throws Exception
     */
    public function showBtnAction($btnAction, int $noLine)
    {
        if ($btnAction instanceof ODButton) {
            $btnAction = $btnAction->id;
        }
        if (!is_string($btnAction)) {
            throw new InvalidArgumentException("demande de suppression visibilité de bouton action, passer soit un objet ODButton soit un identifiant");
        }
        $datas = $this->datas;
        if (!array_key_exists($noLine, $datas)) {
            throw new InvalidArgumentException("Numéro de ligne $noLine inexistant");
        }
        $btnsActionsHidden = $this->btnsActionsHidden;
        if (array_key_exists($noLine, $btnsActionsHidden)) {
            $btns = explode('|', $btnsActionsHidden[$noLine]);
            if (in_array($btnAction, $btns)) {
                unset($btns[array_search($btnAction, $btns)]);
            }
            if (!empty($btns)) {
                $btnsActionsHidden[$noLine] = implode('|', $btns);
            } else {
                unset($btnsActionsHidden[$noLine]);
            }
        }
        $this->btnsActionsHidden = $btnsActionsHidden;
        return $this;
    }
}