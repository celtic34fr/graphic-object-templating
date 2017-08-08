<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;
use graphicObjectTEmplating\Objects\OObject;

/**
 * Class ODMenu
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * addLeaf(array $item, $parent = null)
 * setMenuItem($idMenu, array $option)
 * getMenuItem($idMenu)
 * setMenuPath($idMenu, $optionPath)
 * getMenuPath($idMenu)
 * clearMenu()
 * getActivMenu()
 * setActivMenu($idMenu)
 * clearActivMenu()
 * geBreadcrumbsDatas()
 * setTitle($title)
 * getTitle()
 * setOptionIcon($idOption, $icon)
 * getOptionIcon($idOption)
 */
class ODMenu extends ODContained
{
    const FILE_ICO = 'ico';
    const FILE_PNG = 'png';
    const FILE_GIF = 'gif';
    const FILE_JPG = 'jpg';

    protected $const_file;

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
                $tmpPath = $dataPath[$parent];
                $dataPath[$item['id']] = $dataPath[$parent] .".". $item['id'];
                $tmpPath = explode('.', $tmpPath);
                $dataTree = $this->insertLeaf($dataTree, $tmpPath, $item);
            } else {
                $tmpPath = "";
                $dataPath[$item['id']] = $item['id'];
                $dataTree[$item['id']] = $item;
            }

            $properties['dataPath'] = $dataPath;
            $properties['dataTree'] = $dataTree;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function setMenuItem($idMenu, array $option)
    {
         $option = $this->validArrayOption($option);
         if ($option) {
             $properties = $this->getProperties();
             $dataTree   = $properties['dataTree'];
             $dataPath   = $properties['dataPath'];
             $paths = $dataPath[$idMenu];
             $paths = explode('.', $paths);

             $dataTree = $this->affectOption($paths, $option, $dataTree);
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

    public function setMenuPath($idMenu, $optionPath)
    {
        $idMenu = (string) $idMenu;
        $optionPath = (string) $optionPath;
        $properties = $this->getProperties();
        $dataPath   = $properties['dataPath'];
        if (!array_key_exists($idMenu, $dataPath)) { return false; }

        $dataPath[$idMenu] = $optionPath;
        $properties['dataPath'] = $dataPath;
        $this->setProperties($properties);
        return $this;
    }

    public function getMenuPath($idMenu)
    {
        $properties = $this->getProperties();
        $menusPath  = $properties['dataPath'];
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

    public function setTitle($title)
    {
        $title = (string) $title;
        $properties = $this->getProperties();
        $properties['title'] = $title;
        $this->setProperties($properties);
        return $this;
    }

    public function getTitle()
    {
        $properties          = $this->getProperties();
        return ((!empty($properties['title'])) ? $properties['title'] : false) ;
    }

    public function setOptionIcon($idOption, $icon) {
        $idOption = (string) $idOption;
        $icon     = (string) $icon;
        $properties = $this->getProperties();
        $dataPath   = $properties['dataPath'];
        $dataTree   = $properties['dataTree'];
        if (array_key_exists($idOption, $dataPath)) {
            $paths = $dataPath[$idOption];
            $paths = explode('.', $paths);
            $dataTree = $this->affectIcon($paths, $icon, $dataTree);
            $properties['dataTree'] = $dataTree;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function setOptionImgIco($idOption, $path, $file)
    {
        if (substr_count($file, '.') === 1 ) {
            $ext = mb_strtolower(substr($file, strpos($file, '.') + 1));
            $exts = $this->getFilesConst();
            if (in_array($ext, $exts)) {
                $filePath = $path.'/'.$file;
                if (file_exists($filePath)) {
                    $option = $this->getMenuItem($idOption);
                    if ((is_array($option))) {
                        $item   = [];
                        $item['path'] = $path;
                        $item['file'] = $file;
                        $option['icon'] = $item;
                        $this->setMenuItem($idOption, $option);

                        return $this;
                    }
                }
            }
        }
        return false;
    }

    public function getOptionIcon($idOption)
    {
        $properties          = $this->getProperties();
        $dataPath            = $properties['dataPath'];
        $dataTree            = $properties['dataTree'];
        $option              = "";
        $idOption = (string) $idOption;
        if (array_key_exists($idOption, $dataPath)) {
            $paths = $dataPath[$idOption];
            $paths = explode('.', $paths);
            $option = $this->returnOption($paths, $dataTree);
        }
        return (empty($option)) ? false : $option;
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

    private function insertLeaf($tree, $path, $item)
    {
        if (!empty($path)) {
            $localPath = $path[0];
            unset($path[0]);
            $path = array_values($path);
            if (!array_key_exists('dropdown', $tree[$localPath])) { $tree[$localPath]['dropdown'] = []; }
            $tree[$localPath]['dropdown'] = $this->insertLeaf($tree[$localPath]['dropdown'], $path, $item);
        } else {
            $tree[$item['id']] = $item;
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

    private function removeMenuItem($menuTree, $idPath)
    {
        if (!empty($idPath)) {
            $localPath                   = $idPath[0];
            unset($idPath[0]);
            if (!empty($idPath)) {
                $menuTree[$localPath] = $this->removeSubTree($menuTree[$localPath], $idPath);
            } else {
                if (isset($menuTree[$localPath]['dropdown'])) unset($menuTree[$localPath]['dropdown']);
            }
        }
        return $menuTree;
    }

    private function removeSubTree($tree, $tmpPath)
    {
        if (!empty($tmpPath)) {
            $localPath                   = $tmpPath[0];
            unset($tmpPath[0]);
            if (!empty($tmpPath)) {
                $tree[$localPath] = $this->removeSubTree($tree[$localPath], $tmpPath);
            } else {
                if (isset($tree[$localPath]['dropdown'])) unset($tree[$localPath]['dropdown']);
            }
        }
        return $tree;
    }

    private function affectIcon($paths, $icon, $tree) {
        if (!empty($paths)) {
            $localPath = $paths[0];
            unset($paths[0]);
            $paths = array_values($paths);
            $tree[$localPath] = $this->affectIcon($paths, $icon, $tree[$localPath]);
        } else {
            $tree['icon'] = $icon;
        }
        return $tree;
    }

    private function affectOption($paths, $option, $tree) {
        if (!empty($paths)) {
            $localPath = $paths[0];
            unset($paths[0]);
            $paths = array_values($paths);
            $tree[$localPath] = $this->affectOption($paths, $option, $tree[$localPath]);
        } else {
            $tree = $option;
        }
        return $tree;
    }

    private function returnOption($paths, $tree) {
        if (!empty($paths)) {
            $localPath = $paths[0];
            unset($paths[0]);
            $paths = array_values($paths);
            $retour = $tree[$localPath] = $this->returnOption($paths, $tree[$localPath]);
       } else {
            $retour = $tree;
       }
       return $retour;
    }

    private function getFilesConst()
    {
        $retour = [];
        if (empty($this->const_nature)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'FILE');
                if ($pos !== false) {
                    $retour[$key] = $constant;
                }
            }
            $this->const_file = $retour;
        } else {
            $retour = $this->const_file;
        }
        return $retour;
    }
}