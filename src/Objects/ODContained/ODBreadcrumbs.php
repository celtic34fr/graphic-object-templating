<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;
use graphicObjectTEmplating\Objects\OObject;
use Zend\Session\Container;

/**
 * Class ODBreadcrumbs
 * @package GoTemplating\Objects\ODContained
 *
 * setTree      : initialise le fil d'ariane avec le contenu du tableau $tree [['label', 'url'], ...]
 * getTree      : restitue de tableau permettant de construire le fil d'Ariane
 * addItem      : ajoute à la suite un nouvel élément du fil d'Ariane
 * removeItem   : supprime le dernier élément du fil d'ariane
 * clearTree    : supprime tout le contenu du fil d'arraine
 * setLabel     : affectation du texte présenté devant le fil d'Ariane
 * getLabel     : récupération du texte présenté devant le fil d'Ariane
 * setSeparator : permet de donner un caractère affichable de séparation dans le fil d'Arine
 * getSeparator : restitue le caractère actuel de séparation dans le fil d'Ariane
 */

class ODBreadcrumbs extends ODContained
{
    public function __construct($id)
    {
        parent::__construct($id, "oobjects/odcontained/odbreadcrumbs/odbreadcrumbs.config.php");
        $this->id = $id;
        $this->setDisplay();
        return $this;
    }

    public function setTree(array $tree)
    {
        $properties = $this->getProperties();
        $properties['tree'] = $tree;
        $this->setProperties($properties);
        return $this;
    }

    public function getTree()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('tree', $properties)) ? $properties['tree'] : false);
    }

    public function addItem($label, $url, $icon = null)
    {
        $label = (string) $label;
        $url   = (string) $url;

        if (!empty($label) && !empty($url)) {
            $item = [];
            $item['label'] = $label;
            $item['url']   = $url;
            $item['icon']  = '';
            if (!empty($icon)) { $item['icon'] = $icon; }

            $properties    = $this->getProperties();
            $properties['tree'][] = $item;
            $this->setProperties($properties);
            return $this;
        }
    }

    public function removeItem()
    {
        $properties    = $this->getProperties();
        $tree          = $properties['tree'];
        $delItemId     = sizeof($tree) - 1;
        unset($tree[$delItemId]);

        $properties['tree'] = $tree;
        $this->setProperties($properties);
        return $this;
    }

    public function clearTree()
    {
        $properties    = $this->getProperties();
        $properties['tree'] = [];
        $this->setProperties($properties);
        return $this;
    }

    public function setLabel($label)
    {
        $label = (string) $label;
        $properties          = $this->getProperties();
        $properties['label'] = $label;
        $this->setProperties($properties);
        return $this;
    }

    public function getLabel()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('label', $properties)) ? $properties['label'] : false);
    }

    public function setSeparator($separator)
    {
        $separator = (string) $separator;
        if (!empty($separator)) {
            $separator               = substr($separator, 0, 1);
            $properties              = $this->getProperties();
            $properties['separator'] = $separator;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function getSeparator()
    {
        $properties            = $this->getProperties();
        return ((array_key_exists('separator', $properties)) ? $properties['separator'] : false);
    }
}
