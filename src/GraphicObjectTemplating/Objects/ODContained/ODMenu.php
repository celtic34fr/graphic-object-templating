<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;
use graphicObjectTEmplating\Objects\OObject;

/**
 * Class ODMenu
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * addLeaf(array $item, $parent = null)
 * getMenuItem($idMenu)
 * getMenuPath($idMenu)
 * clearMenu()
 * getActivMenu()
 * setActivMenu($idMenu)
 * clearActivMenu()
 * geBreadcrumbsDatas()
 */
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

    public function clearMenu()
    {
        $properties = $this->getProperties();
        $properties['dataTree'] = [];
        $properties['dataPath'] = [];
        $this->setProperties($properties);
        return $this;
    }

    public function removeLeafMenu($idMenu)
    {
        $idPath     = $this->getMenuPath($idMenu);
        if ($idPath !== false) {
            $properties = $this->getProperties();
            $idPath     = explode('.', $idPath);
            $tree       = $properties['dataTree'];
            $localPath  = "";

            if (is_array($idPath)) {
                $localPath          = $idPath[0];
                unset($idPath[0]);
                $tree[$localPath]   = $this->removeMenuItem($tree[$localPath], $idPath);
            } else {
                $localPath = $idPath;
                unset($tree[$localPath]);
            }

            $properties['dataPath'] = $this->removeMenuPath($idMenu);
            $properties['dataTree'] = $tree;
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

    public function setActivMenu($idMenu)
    {
        $idPath  = $this->getMenuPath($idMenu);
        $tabPath = explode('.', $idPath);
        if ($idPath !== false) {
            $properties              = $this->getProperties();
            $this->clearActivMenu();
            $properties['dataTree']  = $this->activeTree($properties['dataTree'], $tabPath);
            $properties['activMenu'] = $idPath;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function clearActivMenu()
    {
        $properties              = $this->getProperties();
        $oldPath                 = $this->getActivMenu();
        $properties['activMenu'] = "";
        $tabPath                 = explode('.', $oldPath);
        $properties['dataTree']  = $this->deactiveTree($properties['dataTree'], $tabPath);
        $this->setProperties($properties);
        return $this;
    }

    // génération d'un tableau d'alimentation de l'objet ODBreadcrumbs en fonction de l'option de menu active
    public function genBreadcrumbsDatas()
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


    private function validArrayOption(array $item)
    {
        $aRetour = [];
        $refArray = ['id', 'label', 'icon', 'badge', 'link', 'target', 'state'];
        foreach ($refArray as $ref) { if (!array_key_exists($ref, $item)) { $item[$ref] = ""; } }
        foreach ($item as $key => $value) {
            if (!in_array(strtolower($key), $refArray)) { return false; }
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

    private function activeTree($tree, $path)
    {
        if (!empty($path)) {
            $localPath = $path[0];
            unset($path[0]);

            $tree[$localPath]['active'] = true;
            if (isset($tree[$localPath]['dropdown']))
                $tree[$localPath]['dropdown'] = $this->activeTree($tree[$localPath]['dropdown'], $path);
        }
        return $tree;
    }

    private function deactiveTree($tree, $path)
    {
        if (!empty($path) && !is_array($path)) {
            $tree[$path]['active'] = false;
        } elseif (!empty($path)) {
            $localPath = $path[0];
            unset($path[0]);

            $tree[$localPath]['active'] = false;
            $tree[$localPath]['dropdown'] = $this->deactiveTree($tree[$localPath]['dropdown'], $path);
        }
        return $tree;
    }

    private function removeMenuPath($idMenu)
    {
        $validPath = $this->getMenuPath($idMenu);
        if ($validPath !== false) {
            $proerties = $this->getProperties();
            unset($proerties['dataPath'][$idMenu]);
            $this->setProperties($proerties);
            return $this;
        }
        return false;
    }

    private function removeMenuItem($menuTre, $idPath)
    {
        if (!empty($idPath)) {
            $localPath                   = $idPath[0];
            unset($idPath[0]);
            if (!empty($idPath)) {
                $tree[$localPath] = $this->removeSubTree($tree[$localPath], $tmpPath);
            } else {
                if (isset($tree[$localPath]['dropdown'])) unset($tree[$localPath]['dropdown']);
            }
        }
        return $tree;
    }
}