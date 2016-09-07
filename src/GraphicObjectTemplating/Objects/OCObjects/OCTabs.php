<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 03/09/16
 * Time: 20:26
 */

namespace GraphicObjectTemplating\Objects\OCObjects;

use GraphicObjectTemplating\Objects\ODContained\ODButton;
use GraphicObjectTemplating\Objects\OSContainer;

/**
 * Class OCTabs
 * @package GraphicObjectTemplating\Objects\OCObjects
 *
 * setTitle
 * getTitle
 * addTab
 * setTab
 * setTabs
 * getTab
 * getTabs
 * showTab
 * hideTab
 * enaNavigate
 * disNavigate
 * setActivTab
 * getActivTab
 */
class OCTabs extends OSContainer
{
    /** @var  ODButton $btnNext */
    protected $btnNext;
    /** @var  ODButton $btnPrevious */
    protected $btnPrevious;
    /** @var  ODButton $btnFirst */
    protected $btnFirst;
    /** @var  ODButton $btnLast */
    protected $btnLast;

    public function __construct($id)
    {
        parent::__construct($id, "oobject/ocobjects/octabs/octabs.config.phtml");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
    }

    public function setTitle($title)
    {
        $title = (string) $title;
        $properties  = $this->getProperties();
        $properties['title'] = $title;
        $this->setProperties($properties);
        return $this;
    }

    public function getTitle()
    {
        $properties = $this->getProperties();
        return (array_key_exists('title', $properties)) ? $properties['title'] : false;
    }

    public function addTab($titleTab, OSContainer $tab)
    {
        $titleTab = (string) $titleTab;
        if (!empty($titleTab)) {
            $properties = $this->getProperties();
            $item = [];
            $item['title']   = $titleTab;
            $item['content'] = $tab;
            $item['show']    = true;
            $item['activ']   = false;

            $idx = sizeof($properties['tabs']) + 1;
            $properties['tabs'][$idx] = $item;
            $this->setProperties($properties);
            return $this;
        }
        return false;
    }

    public function setTab($idx, $titleTab = null, OSContainer $tab = null)
    {
        if ($titleTab == null && $tab == null) return false;
        $idx = (int) $idx;
        $properties = $this->getProperties();
        $maxxIdx    = sizeof($properties['tabs']);
        if ($idx < 1 || $idx > $maxxIdx) return false;

        if ($titleTab != null) $properties['tabs'][$idx]['title'] = $titleTab;
        if ($tab != null && $tab instanceof OSContainer) $properties['tabs'][$idx]['content'] = $tab;
        $this->setProperties($properties);
        return $this;
    }

    public function setTabs(array $tabs)
    {
        $properties = $this->getProperties();
        $properties['tabs'] = $tabs;
        $this->setProperties($properties);
        return $this;
    }

    public function getTab($idx)
    {
        $idx = (int) $idx;
        $properties = $this->getProperties();
        $maxxIdx    = sizeof($properties['tabs']);
        if ($idx < 1 || $idx > $maxxIdx) return false;

        return $properties['tabs'][$idx];
    }

    public function getTabs()
    {
        $properties = $this->getProperties();
        return (array_key_exists('tabs', $properties)) ? $properties['tabs'] : false;
    }

    public function showTab($idx)
    {
        $idx = (int) $idx;
        $properties = $this->getProperties();
        $maxxIdx    = sizeof($properties['tabs']);
        if ($idx < 1 || $idx > $maxxIdx) return false;

        $properties['tabs'][$idx]['show'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function hideTab($idx)
    {
        $idx = (int) $idx;
        $properties = $this->getProperties();
        $maxxIdx    = sizeof($properties['tabs']);
        if ($idx < 1 || $idx > $maxxIdx) return false;

        $properties['tabs'][$idx]['show'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function enaNavigate()
    {
        $properties = $this->getProperties();

        $properties['navigation'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function disNavigate()
    {
        $properties = $this->getProperties();

        $properties['navigation'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function setActivTab($tab)
    {
        $tab = (int) $tab;
        $properties = $this->getProperties();
        $maxTab = sizeof($properties['tabs']);
        if ($tab < 1 || $tab > $maxTab) return false;

        $properties['tabs'][$tab]['activ'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function getActivTab()
    {
        $properties = $this->getProperties();
        if (array_key_exists('tabs', $properties)) {
            if (!empty($properties['tabs'])) {
                foreach ($properties['tabs'] as $idx => $tab) {
                    if ($tab['activ'] === true) return $idx;
                }
            }
        }
        return false;
    }

}