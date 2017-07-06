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
 * addItem      : ajoute à la suite un nouvel élément du fil d'ariane
 * removeItem   : supprime le dernier élément du fil d'ariane
 * clearTree    : supprime tout le contenu du fil d'arraine
 * setLabel     : affectation du texte présenté devant le fil d'aAriane
 * getLabel     : récupération du texte présenté devant le fil d'aAriane
 */

class ODBreadcrumbs extends ODContained
{
    public function __construct($id)
    {
        $parent = parent::__construct($id, "oobject/odcontained/odbreadcrumbs/odbreadcrumbs.config.php");
        $this->properties = $parent->properties;
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

    public function addItem($label, $url)
    {
        $label = (string) $label;
        $url   = (string) $url;

        if (!empty($label) && !empty($url)) {
            $item = [];
            $item['label'] = $label;
            $item['url']   = $url;

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
}
