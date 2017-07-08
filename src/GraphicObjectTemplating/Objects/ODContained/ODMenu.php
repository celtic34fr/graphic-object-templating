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