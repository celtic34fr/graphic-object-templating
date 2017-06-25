<?php

namespace GraphicObjectTemplating\Objects\ODContained;


use GraphicObjectTemplating\Objects\ODContained;

class ODTreeview extends ODContained
{

    /**
     * @param $label        : text devant être présenté à l'écran
     * @param $value        : valeur associé au label
     * @param null $parent  : valeur associé à la feuille parente
     * @param null $enable  : sélectionable ou non (true / false)
     *
     * @return $this|bool   : objet treeview ou false si erreur
     */
    public function addLeaf($label, $value, $parent = NULL, $enable = true, $select = false, $check = false)
    {
        // ajout d'une feuille à l'arbre
        $properties = $this->getProperties();
        $tree = [];
        $data = [];
        if (isset($properties['tree'])) {
            $tree = $properties['tree'];
            $data = $properties['data'];
        }

        if ((!empty($data)) and (array_key_exists($value, $data))) return false;

        $item = [];
        $item['text']   = $label;
        $item['value']  = $value;
        $item['parent'] = $parent;
        $item['enable'] = $enable;
        $item['select'] = $select;
        $item['check']  = $check;
        $leafTree       = $item;
        $item['key']    = NULL;
        $leafData       = $item;

        if (empty($parent)) {
            $tree[] = $leafTree;
            $leafData['key'] = strval(sizeof($tree) -1);
            $data[$value] = $leafData;
        } else {
            if ((!empty($data)) and (!array_key_exists($parent, $data))) {
                throw new \Exception("feuille objet treeview : $parent n'existe pas");
                return false;
            }

            $max   = $properties['maxLevel'] - 2;
            $count = substr_count($data[$parent]['key'], ".");
            if ($count > $max) return false;

            $tmp = $this->affectLevel($tree, $leafTree, $data[$parent]['key']);
            $leafData['key'] = $tmp['key'];
            $tree            = $tmp['tree'];
            $data[$value]    = $leafData;
        }
        if ($tree !== false) {
            $properties['data'] = $data;
            $properties['tree'] = $tree;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    /**
     * @return array / bool
     */
    public function getData()
    {
        $properties = $this->getProperties();
        if (isset($properties['data'])) return $properties['data'];
        return false;
    }

    public function setData(array $data)
    {
        $prot = $this->getProperties();
        $prot['data'] = $data;
        $this->setProperties($prot);
    }

    /**
     * @param array $leaves
     */
    public function setLeaves(array $leaves)
    {
        $data_leaves = $leaves;
        $parents = [];
        $values  = [];
        $this->clearTree();

        foreach ($data_leaves as $key => $data_leaf) {
            if ($data_leaf['parent'] == NULL) {
                $data_leaf['parent'] = $data_leaf['value'];
            }
            $parents[$key] = $data_leaf['parent'];
            $values[$key]  = $data_leaf['value'];
        }
        array_multisort($parents, SORT_ASC, $values, SORT_ASC, $data_leaves);

        foreach ($data_leaves as $data_leaf) {
            if ($data_leaf['parent'] == $data_leaf['value'])
            {
                $this->addLeaf($data_leaf['text'], $data_leaf['value'], NULL, $data_leaf['enable'],
                    $data_leaf['select'], $data_leaf['check']);
            } else {
                $this->addLeaf($data_leaf['text'], $data_leaf['value'], $data_leaf['parent'], $data_leaf['enable'],
                    $data_leaf['select'], $data_leaf['check']);
            }
        }
    }

    /**
     * @return array /bool  : tableau tree ou false s'il n'existe pas
     */
    public function getTree()
    {
        $properties = $this->getProperties();
        if (isset($properties['tree'])) return $properties['tree'];
        return false;
    }

    /**
     * @return $this|bool   : vidage du tableau des feuilles de l'arbre
     */
    public function clearTree()
    {
        $properties = $this->getProperties();
        if (isset($properties['tree'])) {
            $tree = [];
            $properties['tree'] = $tree;
            $properties['data'] = $tree;
            $this -> setProperties($properties);
            return $this;
        }
        return false;
    }

    /**
     * @param $tmpTree      : partie d'arbre à exploiter
     * @param $tmpParent    : sous chaîne de qualification de la feuille parente
     * @param $item         : feuille à ajouter
     *
     * @return array /bool  : partie de l'abre après traitement ou false si erreur
     */
    private function affectLevel($tmpTree, $item, $tmpParent = NULL)
    {
        $retour = [];
        switch(true) {
            case ($tmpParent == NULL) :
                $ind = sizeof($tmpTree) + 1;
                $tmpTree[$ind] = $item;
                $retour['key']  = strval($ind);
                $retour['tree'] = $tmpTree;
                break;
            case ($tmpParent != NULL) :
                $pos =strpos($tmpParent, ".");
                switch(true) {
                    case ($pos === false) :
                        $parent = intval($tmpParent);
                        $ltree = $tmpTree[$parent];
                        if (!isset($ltree['nodes'])) $ltree['nodes'] = [];
                        $tree = $this->affectLevel($ltree['nodes'], $item);
                        $ltree['nodes'] = $tree['tree'];
                        $tmpTree[intval($tmpParent)] = $ltree;

                        $retour['key']  = strval(intval($tmpParent)).".".$tree['key'];
                        $retour['tree'] = $tmpTree;
                        break;
                    case ($pos !== false) :
                        $lparent = substr($tmpParent, 0, $pos);
                        $rparent = substr($tmpParent, $pos + 1);
                        $ltree = $tmpTree[$lparent];
                        if (!isset($ltree['nodes'])) $ltree['nodes'] = [];
                        $tree = $this->affectLevel($ltree['nodes'], $item, $rparent);
                        $ltree['nodes'] = $tree['tree'];
                        $tmpTree[$lparent] = $ltree;

                        $retour['key']  = strval($lparent).".".$tree['key'];
                        $retour['tree'] = $tmpTree;
                        break;
                }
                break;
        }
        return $retour;
    }

    /**
     * @param string $arrayCol : étiquette mise avant l'arbre, affectation
     */
    public function setLabel($arrayCol = "")
    {
        $prot = $this->getProperties();
        $prot['label'] = $arrayCol;
        $this->setProperties($prot);
    }

    /**
     * @return bool|mixed : étiquette mise avant l'arbre, restitution
     */
    public function getLabel()
    {
        $properties = $this->getProperties();
        if (isset($properties['label'])) return $properties['label'];
        return false;
    }

    /**
     * @param bool $value : détermine si l'on peut sélectionner plusieurs lignes (true) ou une seule (false)
     */
    public function setMultiple($value=true){
        $properties = $this->getProperties();
        $properties['multiple'] = $value;
        $this->setProperties($properties);
    }

    /**
     * @return bool|mixed
     */
    public function getMultiple()
    {
        $properties = $this->getProperties();
        if (isset($properties['multiple'])) return $properties['multiple'];
        return false;
    }

    /**
     * @return bool : restitue si l'on peut sélectionner plusieurs lignes (true) ou une seule (false)
     */
    public function getSelected()
    {
        $properties = $this->getProperties();
        if (isset($properties['selected'])) return $properties['selected'];
        return false;
    }

    /**
     * @param array $values : valeur à mettre à sélectionné
     */
    public function setSelected(array $values)
    {
        $prot = $this->getProperties();
        $datas = $prot['data'];
        $tree  = $prot['tree'];
        foreach ($values as $value) {
            if (array_key_exists($value, $datas)) {
                $datas[$value]['select'] = true;
                $retour = $this->affectTree($tree, $value, $datas[$value]['check'], $datas[$value]['select']);
                if ($retour['find'] === true) $tree = $retour['tree'];
            }
        }
        $prot['data'] = $datas;
        $prot['tree'] = $tree;
        $this->setProperties($prot);
        $this->setLeaves($datas);
    }

    /**
     * @param array $values : désélection globale
     */
    public function clearSelected()
    {
        $prot = $this->getProperties();
        $datas = $prot['data'];
        $tree  = $prot['tree'];
        foreach ($datas as $key => $data) {
            $datas[$key]['select'] = false;
            $retour = $this->affectTree($tree, $key, $datas[$key]['check'], $datas[$key]['select']);
            if ($retour['find'] === true) $tree = $retour['tree'];
        }
        $prot['data'] = $datas;
        $prot['tree'] = $tree;
        $prot['selected'] = [];
        $this->setProperties($prot);
        $this->setLeaves($datas);
    }

    /**
     * @return bool
     */
    public function getChecked()
    {
        $properties = $this->getProperties();
        if (isset($properties['checked'])) return $properties['checked'];
        return false;
    }

    /**
     * @param array $values
     */
    public function setChecked(array $values)
    {
        $prot = $this->getProperties();
        $datas = $prot['data'];
        $tree  = $prot['tree'];
        foreach ($values as $value) {
            if (array_key_exists($value, $datas)) {
                $datas[$value]['check'] = true;
                $retour = $this->affectTree($tree, $value, $datas[$value]['check'], $datas[$value]['select']);
                if ($retour['find'] === true) $tree = $retour['tree'];
            }
        }
        $prot['data'] = $datas;
        $prot['tree'] = $tree;
        $this->setProperties($prot);
        $this->setLeaves($datas);
    }

    public function clearChecked()
    {
        $prot = $this->getProperties();
        $datas = $prot['data'];
        $tree  = $prot['tree'];
        foreach ($datas as $key => $data) {
            $datas[$key]['check'] = false;
            $retour = $this->affectTree($tree, $key, $datas[$key]['check'], $datas[$key]['select']);
            if ($retour['find'] === true) $tree = $retour['tree'];
        }
        $prot['data'] = $datas;
        $prot['tree'] = $tree;
        $prot['selected'] = [];
        $this->setProperties($prot);
        $this->setLeaves($datas);
    }

    /**
     * @param $maxLevel : fixe le nombre maximum de niveau dans l'arbre (profondeur)
     */
    public function setMaxLevel($maxLevel)
    {
        $maxLevel = (int) $maxLevel;
        if ($maxLevel == 0) $maxLevel = 2; // nombre de niveau par défaut

        $prot = $this->getProperties();
        $prot['maxLevel'] = $maxLevel;
        $this->setProperties($prot);
    }

    /**
     * @return bool|mixed : restitue le nombre maximum de niveau dans l'arbre (profondeur)
     */
    public function getMaxLevel()
    {
        $properties = $this->getProperties();
        if (isset($properties['maxLevel'])) return $properties->maxLevel;
        return false;
    }

    /**
     * déclecnche l'affichage de case à cocher devant le label de chaque feuille
     */
    public function showCheckBox()
    {
        $prot = $this->getProperties();
        $prot['checkbox'] = true;
        $this->setProperties($prot);
    }

    /**
     * supprime l'affichage de case à cocher devant le label de chaque feuille
     */
    public function hideCheckBox()
    {
        $prot = $this->getProperties();
        $prot['checkbox'] = false;
        $this->setProperties($prot);
    }

    public function activeSearching (){
        $prot = $this->getProperties();
        $prot['search'] = true;
        $this->setProperties($prot);
    }

    public function disableSearching (){
        $prot = $this->getProperties();
        $prot['search'] = false;
        $this->setProperties($prot);
    }

    public function setSearchPlaceholder ($label){
        $prot = $this->getProperties();
        $prot['searchPlaceholder'] = $label;
        $this->setProperties($prot);
    }

    public function setSearchLabel ($label){
        $prot = $this->getProperties();
        $prot['searchLabel'] = $label;
        $this->setProperties($prot);
    }

    private function affectTree($tree, $valeur, $check, $select)
    {
        foreach ($tree as $ind => $leaf) {
            if ($leaf['value'] == $valeur) {
                $leaf['check']  = $check;
                $leaf['select'] = $select;
                $tree[$ind] = $leaf;
                return array('find' => true, 'tree' => $tree);
            } else {
                if ((isset($leaf['nodes'])) && (!empty($leaf['nodes']))) {
                    $retour = $this->affectTree($leaf['nodes'], $valeur, $check, $select);
                    if ($retour['find'] === true) {
                        $leaf['nodes'] = $retour['tree'];
                        $tree[$ind] = $leaf;
                        return array('find' => true, 'tree' => $tree);
                    }
                }
            }
        }
        return array('find' => false, 'tree' => $tree);
    }

    private function getrealip()
    {
        return implode("_",explode(".",$_SERVER["REMOTE_ADDR"]));
    }

}