<?php
/**
 * Created by PhpStorm.
 * User: candidat
 * Date: 06/07/17
 * Time: 16:55
 */

namespace GraphicObjectTemplating\Objects\ODContained;


use GraphicObjectTemplating\Objects\ODContained;
use graphicObjectTEmplating\Objects\OObject;

class ODMenu extends ODContained
{
    public function __construct($id)
    {
        parent::__construct($id, "oobject/odcontained/odmenu/odmenu.config.php");
        $this->id = $id;
        $this->setDisplay();
        return $this;
    }

    public function addLeaf(array $item, $parent = null)
    {
        $item           = $this->validArrayOption($item);
        if (!$item)     { return false; } // erreur dans le tableau [champs en trop]
        $state          = 'ENABLED';
        $select         = false;
        if (array_key_exists('state', $item)) {
            $state      = $item['state'];
            unset($item['state']);
            if ($state === 'SELECTED') {
                $state  = 'ENABLED';
                $select = true;
            }
        }
        $item['enable'] = $state;
        $item['select'] = $select;

        $properties     = $this->getProperties();
        $dataPath       = $properties['dataPath'];
        $dataTree       = $properties['dataTree'];
        if (!array_key_exists($item['id'], $dataPath)) {
            if (!empty($parent)) {
                $dataPath[$item['id']] = $dataPath[$parent] .".". $item['id'];
            } else {
                $dataPath[$item['id']] = $item['id'];
            }
            $dataTree = $this->insertLeaf($dataTree, $dataPath, $item, $parent);

            $properties['dataPath'] = $dataPath;
            $properties['dataTree'] = $dataTree;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getActivMenu()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['activMenu'])) ? $properties['activMenu'] : false) ;
    }

    // génération d'un tableau d'alimentation de l'objet ODBreadcrumbs en fonction de l'option de menu active
    public function genBreadcrumbsData()
    {
        $properties = $this->getProperties();
        $arrayDatas = [];
        $activMenu  = $this->getActivMenu();
        if ($activMenu !== false) {
            $menuPath = $this->getMenuPath($activMenu);
            $menuPath = explode('.', $menuPath);
            $menuTree = $properties['dataTree'];
            foreach ($menuPath as $menuItem) {
                $item = [];
                $item['label'] = $menuTree[$menuItem]['label'];
                $item['url']   = $menuItem[$menuItem]['link'];
                $arrayDatas    = $item;
                if (isset($menuTree[$menuItem]['dropdown'])) {
                    $menuITree     = $menuTree[$menuItem]['dropdown'];
                }
            }
            return $arrayDatas;
        }
        return false;
    }

    public function getMenuItem($idMenu)
    {
        $properties = $this->getProperties();
        $menusItem  = $properties('dataTree');
        $menuPath   = $this->getMenuPath($idMenu);
        $menuPath   = explode('.', $menuPath);

        $trouve = false;
        foreach ($menuPath as $menuItem) {
            if (array_key_exists($menuItem, $menusItem)) {
                $menusItem = $menusItem[$menuItem];
                $trouve = true;
                if (isset($menusItem['dropdown'])) {
                    $menusItem = $menusItem['dropdown'];
                }
            }
            if ($trouve) {
                $trouve = false;
            } else {
                // item partie du chemin d'accès à idMenu n'existe pas => anomalie
                return false;
            }
        }
        if ($trouve) {
            return $menusItem;
        } else {
            // $idMenu n'existe pas => anomalie
            return false;
        }
    }

    public function getMenuPath($idMenu)
    {
        $properties = $this->getProperties();
        $menusPath  = $properties('dataPath');
        if (array_key_exists($idMenu, $menusPath)) {
            return $menusPath[$idMenu];
        }
        return false;
    }


    private function validArrayOption(array $item)
    {
        $aRetour = [];
        $refArray = ['id', 'label', 'icon', 'badge', 'link', 'target', 'state'];
        foreach ($refArray as $ref) { if (!array_key_exists($ref, $item)) { $item[$ref] = ""; } }
        foreach ($item as $key => $value) {
            if (!array_key_exists(strtolower($key), $refArray)) { return false; }
            $aRetour[strtolower($key)] = $value;
        }
        return $aRetour;
    }

    private function insertLeaf($tree, $path, $item, $parent = null)
    {
        switch (true) {
            case ($parent == null) :
                $tree[$item['id']] = $item;
                break;
            case ($parent != null) :
                $tmpPath = $path[$parent];
                $tmpPath = explode(".", $tmpPath);
                $nParent = sizeOf($tmpPath);
                if (!isset($tree[$parent]['dropdown'])) { $tree[$parent]['dropdown'] = []; }
                $localPath = ($nParent > 1) ? $tmpPath[$nParent - 2] : null;
                $tree[$parent]['dropdown'] = $this->insertLeaf($tree[$parent]['dropdown'], $path, $item, $localPath);
                break;
        }
        return $tree;
    }
}