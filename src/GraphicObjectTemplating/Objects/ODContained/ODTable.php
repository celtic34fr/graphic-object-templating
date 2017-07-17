<?php

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;
use graphicObjectTEmplating\Objects\OObject;
use Zend\Session\Container;

/**
 * Class ODTable
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * !setTitle($title, $position = "bottom_center") : affecte un titre ou légende au tableau
 * !getTitle()                             : restitué le titre ou légende du tableau
 * !setTitlePos($position = "bottom_center") : affecte la position du titre du tableau
 * !getTitlePos()                          : restitue le chaîne de caractère qualifiant la position du titre
 * !addTitleStyle($style)                  : ajoute le contenu de la chaine $style au style actuel du titre
 * !setTitleStyle($style)                  : affecte un style au titre du tableau
 * !getTitleStyle()                        : restitue le style actuel du titre du tableau
 * !initColsHead(array())                  : affecte au colonnes leurs titre ou entêtes
 * !getColsHead()                          : restitue les titres des colonnes
 * !setColsWidth(array $width)
 * !setColWidth($nCol, $width)
 * !addLine(array())                       : ajoute une ligne de données au tableau
 * !setLine(nLine, array())                : ajout ou met à jour la ligne nLine dans le tableau
 * !setLines(array())                      : remplace l'ensermble des lignes du tableau avec le contenu de array()
 * !getLine(nLine)                         : restitue le tableau de valeur formant la ligne nLine
 * !getLines()                             : restitue le tableau des tableau de valeurs formant les lignes du tableau
 * !removeLine(nLine)                      : supprime la ligne nLine du tableau (les lignes nLine + 1 à fin deviennent nLine à fin -1)
 * !removeLines()                          : supprime toutes les lignes de données du tableau (par les entêtes de colonnes)
 * !clearTable()                           : supprime tout le contenu du tableau, entête comprise
 * !addColTitle(title[, array(),[, nCol]]) : ajoute une colone en fin de tableau (niveau entête) si nCol est indiqué, insersion en colone nCol
 * !setColValues(nCol, array())            : remplace les valeurs contenues dans la colonne nCol avec le tabllau array() (mise à vide de indice manquant)
 * !getCol(nCol)                           : restitue le contenu de la colonne nCol sous forme d'un array()
 * !removeCol(nCol)                        : supprimme la colonne nCol en valeurs et entête
 * !setCell(nCol,nLine, value)             : affecte à la colone nCol, ligne nLine, le contenu value
 * !getCell(nCol, nLine)                   : restitue le cotenu de la cellule colone nCol, ligne nLine
 * !addTableStyle(style)                   : ajout le contenu de la chaîne style au style actuel du tableau
 * !setTableStyle(style)                   : affecte au style du tableau le contenu de la chaîne style
 * !getTableStyle()                        : restitue le style actuel du tableau
 * !clearTableStyle()                      : supprime tout style au tableau (niveau tableau, pas lignes ou colonnes)
 * !toggleTableStyle([style])              : permutte le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne style avec l'actuel qu'il sauvegarde alors
 * !addColStyle(nCol, style)               : ajout le contenu de la chaîne style au style actuel de la colonne nCol
 * !setColStyle(nCol, style)               : affecte au style de la colonne nCol le contenu de la chaîne style
 * !getColStyle(nCol)                      : restitue le style actuel de la colonne nCol
 * !clearColStyle(nCol)                    : supprime tout style à la colonne nCol
 * !toggleColStyle(nCol[, style])          : permutte le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne style avec l'actuel qu'il sauvegarde alors
 * !addLineStyle(nLine, style)             : ajout le contenu de la chaîne style au style actuel de la ligne nLine
 * !setLineStyle(nLine, style)             : affecte au style de la ligne Nline le contenu de la chaîne style
 * !getLineStyle(nLine)                    : restitue le style actuel de la ligne nLine
 * !clearLineStyle(nLine)                  : supprime tout style à la ligne nLine
 * !toggleLineStyle(nLine[, style])        : permutte le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne style avec l'actuel qu'il sauvegarde alors
 * !addCellStyle(nCol, nLine, style)       : ajout le contenu de la chaîne style au style actuel de la cellule nCol, nLine
 * !setCellStyle(nCol, nLine, style)       : affecte au style de la ligne Nline le contenu de la cellule nCol, nLine
 * !getCellStyle(nCol, nLine)              : restitue le style actuel de la cellule nCol, nLine
 * !clearCellStyle(nCol, nLine)            : supprime tout style à la cellule nCol, nLine
 * !toggleCellStyle(nCol, nLine[, style])  : permutte le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne style avec l'actuel qu'il sauvegarde alors
 * !evtColClick(nCol, callback)            : positionnement un évènement onClick sur la colonne nCol, et demande l'exécution de callback
 * !disColClick(nCol)                      : déactive l'évènement onClick sur la colonne nCol
 * !evtLineClick(nLine, callback)          : positionnement un évènement onClick sur la ligne nLine, et demande l'exécution de callback
 * !disLineClick(nLine)                    : déactive l'évènement onClick sur la ligne nLine
 * !evtCellClick(nCol, nLine, callback)    : positionnement un évènement onClick sur la cellule nCol, nLine, et demande l'exécution de callback
 * !disCellClick(nCol, nLine)              : déactive l'évènement onClick sur la cellule nCol, nLine
 * getSelectedCols()                      : restitue sous forme d'un array() l'ensemble des colonnes sélectionnées
 * setSelectedCol(nCol)                   : sélectionne la colonne nCol pour l'affichage marqué de cette dernière
 * setSelectedCols(array())               : sélection un groupe de colonne déscrite dans array() par leurs numéro pour l'affichage marqué de ces dernières
 * unselectCol(nCol)                      : désélectionne la colonne nCol (si elle l'est) pour affichage non marqué de cette dernières
 * unselectAllCols()                      : désélectionne l'ensble des colonnes sélectionnées (retour total désélectionné)
 * getSelectedLines()                     : restitue sur forme d'un array l'ensemble des lignes sélectionnées
 * setSelectedLine(nLine)                 : sélectionne la ligne nLine pour affichage marqué de cette dernière
 * setSelectedLines(array())              : sélectionne un groupe de ligne décrite dans array() par leurs numéros pour affichage marqué de ces dernières
 * unselectLine(nLine)                    : désélectione la ligne nLine pour affichage non marqsué de cette dernière
 * unselectAllLines()                     : désélectionne l'ensble des lignes sélectionnées (retour total désélectionné)
 * getSelectedCells()                     : restitue sur forme d'un array l'ensemble des cellules sélectionnées
 * setSelectedCell(nCol, nLine)           : sélectionne la cellule nCol, nLine pour affichage marqué de cette dernière
 * setSelectedCells(array())              : sélectionne un groupe de cellules descrites dans array() par leus coordonnées dans le tableau pour affichage marqué de ces dernières
 * unselectCell(nCol, nLine)              : désélectionne la cellule nCol, nLine pour affichage non marqué de cette dernière
 * unselectAllCells()                     : désélectionne l'ensble des cellules sélectionnées (retour total désélectionné)
 * !showLine(nLine)                        : précise que la ligne nLine sera affichée
 * !hideLine(nLine)                        : précise que la ligne nLine ne sera pas affichée
 * !showCol(nCol)                          : précise que la colonne nCol sera affichée
 * !hideCol(nCol)                          : précise que la colonne nCol ne sera pas affichée
 */
class ODTable extends ODContained
{
    const TITLEPOS_TOP_LEFT      = "top_left";
    const TITLEPOS_TOP_CENTER    = "top_center";
    const TITLEPOS_TOP_RIGHT     = "opt_right";
    const TITLEPOS_BOTTOM_LEFT   = "bottom_left";
    const TITLEPOS_BOTTOM_CENTER = "bottom_center";
    const TITLEPOS_BOTTOM_RIGHT  = "bottom_right";

    private $const_titlePos;

    public function __construct($id)
    {
        parent::__construct($id, "oobject/odcontained/odtable/odtable.config.php");
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
        return $this;
    }

    public function setTitle($title, $position = self::TITLEPOS_BOTTOM_CENTER)
    {
        $title = (string)$title;
        $positions  = $this->getTitlePosConstants();
        if (!in_array($position, $positions)) $position = self::TITLEPOS_BOTTOM_CENTER;

        $properties = $this->getProperties();
        $properties['title'] = $title;
        $properties['titlePos'] = $position;
        $this->setProperties($properties);
        return $this;
    }

    public function getTitle()
    {
        $properties = $this->getProperties();
        return (array_key_exists('title', $properties) ? $properties['title'] : false);
    }

    public function setTitlePosition($position = self::TITLEPOS_BOTTOM_CENTER)
    {
        $properties = $this->getProperties();
        $positions  = $this->getTitlePosConstants();
        if (!in_array($position, $positions)) $position = self::TITLEPOS_BOTTOM_CENTER;

        $properties['titlePos'] = $position;
        $this->setProperties($properties);
        return $this;
    }

    public function getTitlePosition()
    {
        $properties = $this->getProperties();
        return (array_key_exists('titlePos', $properties) ? $properties['title'] : false);
    }

    public function addTitleStyle($style)
    {
        $style = (string) $style;
        $properties = $this->getProperties();
        $properties['titleStyle'] .= " " .$style;
        $this->setProperties($properties);
        return $this;
    }

    public function setTitleStyle($style)
    {
        $style = (string) $style;
        $properties = $this->getProperties();
        $properties['titleStyle'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getTitleStyle()
    {
        $properties = $this->getProperties();
        return (array_key_exists('titleStyle', $properties) ? $properties['title'] : false);
    }

    public function initColsHead(array $cols = null)
    {
        if (empty($cols)) return false;
        $properties = $this->getProperties();
        $colsTab = [];
        foreach ($cols as $col) {
            $item = [];
            $item['libel'] = $col;
            $item['view']  = true;
            $colsTab[sizeof($colsTab) + 1] = $item;
        }
        $properties['cols'] = $colsTab;
        $this->setProperties($properties);
        return $this;
    }

    public function getColsHead()
    {
        $properties = $this->getProperties();
        return (!empty($properties['cols']) ? $properties['cols'] : false);
    }

    public function setColsWidth(array $widths = null)
    {
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if (sizeof($widths) == 0 || sizeof($widths) != $nbCols) return false;

        foreach ($widths as $key => $width) {
            $properties['cols'][$key]['width'] = $width;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function setColWidth($nCol, $width)
    {
        $nCol = (int) $nCol;
        $width = (string) $width;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nCol < 1 || $nCol > $nbCols) return false;

        $properties['cols'][$nCol]['width'] = $width;
        $this->setProperties($properties);
        return $this;
    }

    public function addLine(array $line = null)
    {
        if (empty($line)) return false;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nbCols != sizeof($line)) return false;

        /* remise en séquence des champs de la ligne */
        $tmp = [];
        foreach ($line as $col) {
            $tmp[sizeof($tmp) + 1] = $col;
        }
        $tmp['view'] = true;
        $properties['datas'][sizeof($properties['datas']) + 1] = $tmp;
        $this->setProperties($properties);
        return (sizeof($properties['datas']));
    }

    public function setLine($nLine, array $line = null)
    {
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        $nbLines = sizeof($properties['datas']);
        if ($nbLines == 0) return false;
        if ($nLine > $nbLines) return false;
        if ($nbCols != sizeof($line)) return false;

        $tmp = [];
        /* remise en séquence des champs de la ligne */
        foreach ($line as $col) {
            $tmp[sizeof($tmp) + 1] = $col;
        }
        $tmp['view'] = true;
        $properties['datas'][$nLine] = $tmp;
        $this->setProperties($properties);
        return $this;
    }

    public function setLines(array $lines = null)
    {
        if (empty($lines)) return false;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        // vérification des lignes de lines qui doivent avoir le bon nombre de colonnes
        foreach ($lines as $line) {
            if ($nbCols != (sizeof($line) - 1)) return false;
        }

        $properties['datas'] = [];
        foreach ($lines as $line) {
            $tmp = [];
            /* remise en séquence des champs de la ligne */
            foreach ($line as $col) {
                $tmp[sizeof($tmp) + 1] = $col;
            }
            $tmp['view'] = true;
            $properties['datas'][sizeof($properties['datas']) + 1] = $tmp;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function getLine($nLine)
    {
        $properties = $this->getProperties();
        $nbLines = sizeof($properties['datas']);
        if ($nbLines == 0) return false;
        if ($nLine == 0 || $nLine > $nbLines) return false;

        return $properties['datas'][$nLine];
    }

    public function getLines()
    {
        $properties = $this->getProperties();
        return (array_key_exists('datas', $properties) ? $properties['datas'] : false);
    }

    public function removeLine($nLine)
    {
        $properties = $this->getProperties();
        $nbLines = sizeof($properties['datas']);
        if ($nbLines == 0) return false;
        if ($nLine == 0 || $nLine > $nbLines) return false;

        /* remise en séquence des lignes restantes */
        for ($i = $nLine; $i < $nbLines - 1; $i++) {
            $properties['datas'][$i] = $properties['datas'][$i + 1];
        }
        unset($properties['datas'][$nbLines]);
        $this->setProperties($properties);
        return $this;
    }

    public function removeLines()
    {
        $properties = $this->getProperties();
        $properties['datas'] = [];
        $this->setProperties($properties);
        return $this;
    }

    public function clearTable()
    {
        $properties = $this->getProperties();
        $properties['cols']   = [];
        $properties['datas']  = [];
        $properties['styles'] = [];
        $properties['event']  = [];
        $this->setProperties($properties);
        return $this;
    }

    public function addColTitle($title, array $cDatas = null, $nCol = 0)
    {
        /* attention les indice de $cDatas doivent commencer à 1 pour le plus petit */
        /* ils sont obligatoirement numérique */
        $title = (string)$title;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        $datas = $properties['datas'];
        if ($nCol > $nbCols || $nCol < 0) return false;

        if ($nCol < 1) { // nCol == 0 : insertion en fin de ligne de la colonne
            $properties['cols'][sizeof($properties['cols']) + 1] = $title;
            foreach ($datas as $key => $data) {
                $data[] = (isset($cDatas[$key])) ? $cDatas[$key] : "";
                $datas[$key] = $data;
            }
        } else {
            $cols = $properties['cols'];
            for ($i = $nbCols; $i > $nCol; $i--) {
                $cols[$i + 1] = $cols[$i];
            }
            $cols[$nCol] = $title;
            $properties['cols'] = $cols;

            foreach ($datas as $key => $data) {
                for ($i = $nbCols; $i > $nCol; $i--) {
                    $data[$i + 1] = $data[$i];
                }
                $data[$nCol] = (isset($cDatas[$key])) ? $cDatas[$key] : "";
                $datas[$key] = $data;
            }
        }
        $properties['datas'] = $datas;
        $this->setProperties($properties);
        return $this;
    }

    public function setColValues($nCol, array $cDatas)
    {
        /* attention les indice de $cDatas doivent commencer à 1 pour le plus petit */
        /* ils sont obligatoirement numérique */
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nCol > $nbCols || $nCol < 1) return false;

        $datas = $properties['datas'];
        foreach ($datas as $key => $data) {
            $data[$nCol] = (isset($cDatas[$key])) ? $cDatas[$key] : "";
            $datas[$key] = $data;
        }
        $properties['datas'] = $datas;
        $this->setProperties($properties);
        return $this;
    }

    public function getCol($nCol)
    {
        $properties = $this->getProperties();
        $nbCols     = sizeof($properties['cols']);
        if ($nCol > $nbCols || $nCol < 1) return false;

        $datas = $properties['datas'];
        $cols  = [];
        foreach ($datas as $data) {
            $cols[sizeof($cols) + 1] = $data[$nCol];
        }
        return $cols;
    }

    public function removeCol($nCol)
    {
        $properties = $this->getProperties();
        $nbCols     = sizeof($properties['cols']);
        if ($nCol > $nbCols || $nCol < 1) return false;

        /* suppression de l'entête */
        $cols = $properties['cols'];
        for ($i = $nCol; $i < $nbCols; $i++) {
            $cols[$i] = $cols[$i + 1];
        }
        unset($cols[$nbCols]);
        $properties['cols'] = $cols;

        /* suppression des données de la colonne */
        $datas = $properties['datas'];
        foreach ($datas as $key => $data) {
            for ($i = $nCol; $i < $nbCols; $i++) {
                $data[$i] = $data[$i + 1];
            }
            unset($data[$nbCols]);
            $datas[$key] = $data;
        }
        $properties['datas'] = $datas;
        $this->setProperties($properties);
        return $this;
    }

    public function setCell($nCol, $nLine, $val)
    {
        $properties = $this->getProperties();
        $nbCols     = sizeof($properties['cols']);
        if ($nCol > $nbCols || $nCol < 1) return false;
        $nbLines = sizeof($properties['datas']);
        if ($nbLines == 0) return false;
        if ($nLine > $nbLines) return false;

        $properties['datas'][$nLine][$nCol] = $val;
        $this->setProperties($properties);
        return $this;
    }

    public function getCell($nCol, $nLine)
    {
        $properties = $this->getProperties();
        $nbCols     = sizeof($properties['cols']);
        if ($nCol > $nbCols || $nCol < 1) return false;
        $nbLines = sizeof($properties['datas']);
        if ($nbLines == 0) return false;
        if ($nLine > $nbLines) return false;

        return (isset($properties['datas'][$nLine][$nCol])) ? $properties['datas'][$nLine][$nCol] : false;
    }

    public function addTableStyle($style)
    {
        $style = (string)$style;
        $properties = $this->getProperties();

        if (!isset($properties['styles'])) $properties['styles'] = [];
        if (!isset($properties['styles'][0])) $properties['styles'][0] = [];
        if (!isset($properties['styles'][0][0])) $properties['styles'][0][0] = "";
        $properties['styles'][0][0] .= " " . $style;
        $this->setProperties($properties);
        return $this;
    }

    public function setTableStyle($style)
    {
        $style = (string)$style;
        $properties = $this->getProperties();

        if (!isset($properties['styles'])) $properties['styles'] = [];
        if (!isset($properties['styles'][0])) $properties['styles'][0] = [];
        $properties['styles'][0][0] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getTableStyle()
    {
        $properties = $this->getProperties();
        return ((isset($properties['styles'][0][0])) ? $properties['styles'][0][0] : false);
    }

    public function clearTableStyle()
    {
        $properties = $this->getProperties();
        if (isset($properties['styles'][0][0])) $properties['styles'][0][0] = null;
    }

    public function toggleTableStyle($style = null)
    {
        $properties = $this->getProperties();
        if (!isset($properties['style'][0][0])) return false;

        $id = $properties['id'];
        $cStyle = $properties['style'][0][0];
        $oStyle = "";
        $session = new Container("styleTable_" . $id);
        if ($session->offsetExists('style')) $oStyle = $session->offsetGet('style');
        $session->offsetSet('style', $cStyle);
        $properties['style'][0][0] = (!empty($style)) ? $style : $oStyle;
        $this->setProperties($properties);
        return $this;
    }

    public function addColStyle($nCol, $style)
    {
        $nCol = (int)$nCol;
        $style = (string)$style;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nCol == 0 ||$nbCols < $nCol) return false;

        if (!isset($properties['styles'])) $properties['styles'] = [];
        if (!isset($properties['styles'][0])) $properties['styles'][0] = [];
        if (!isset($properties['styles'][0][$nCol])) $properties['styles'][0][$nCol] = "";
        $properties['styles'][0][$nCol] .= " " . $style;
        $this->setProperties($properties);
        return $this;
    }

    public function setColStyle($nCol, $style)
    {
        $nCol = (int)$nCol;
        $style = (string)$style;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nCol == 0 ||$nbCols < $nCol) return false;

        if (!isset($properties['styles'])) $properties['styles'] = [];
        if (!isset($properties['styles'][0])) $properties['styles'][0] = [];
        $properties['styles'][0][$nCol] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getColStyle($nCol)
    {
        $nCol = (int)$nCol;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nCol == 0 ||$nbCols < $nCol) return false;

        return ((isset($properties['styles'][0][$nCol])) ? $properties['styles'][0][$nCol] : false);
    }

    public function clearColStyle($nCol)
    {
        $nCol = (int)$nCol;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nCol == 0 ||$nbCols < $nCol) return false;

        if (isset($properties['styles'][0][$nCol])) $properties['styles'][0][$nCol] = "";
        $this->setProperties($properties);
        return $this;
    }

    public function toogleColStyle($nCol, $style = null)
    {
        $nCol = (int)$nCol;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nCol == 0 ||$nbCols < $nCol) return false;
        if (!isset($properties['style'][0][$nCol])) return false;

        $id = $properties['id'];
        $cStyle = (isset($properties['style'][0][$nCol])) ? $properties['style'][0][$nCol] : null;
        $oStyle = "";
        $session = new Container("styleTable_" . $id);
        if ($session->offsetExists('styleC' . $nCol)) $oStyle = $session->offsetGet('styleC' . $nCol);
        $session->offsetSet('styleC' . $nCol, $cStyle);
        $properties['style'][0][$nCol] = (!empty($style)) ? $style : $oStyle;
        $this->setProperties($properties);
        return $this;
    }

    public function addLineStyle($nLine, $style)
    {
        $nLine = (int)$nLine;
        $style = (string)$style;
        $properties = $this->getProperties();
        $nbLines = sizeof($properties['datas']);
        if ($nLine == 0 || $nbLines < $nLine) return false;

        if (!isset($properties['styles'])) $properties['styles'] = [];
        if (!isset($properties['styles'][$nLine])) $properties['styles'][$nLine] = [];
        if (!isset($properties['styles'][$nLine][0])) $properties['styles'][$nLine][0] = "";
        $properties['styles'][$nLine][0] .= " " . $style;
        $this->setProperties($properties);
        return $this;
    }

    public function setLineStyle($nLine, $style)
    {
        $nLine = (int)$nLine;
        $style = (string)$style;
        $properties = $this->getProperties();
        $nbLines = sizeof($properties['datas']);
        if ($nLine == 0 || $nbLines < $nLine) return false;

        if (!isset($properties['styles'])) $properties['styles'] = [];
        if (!isset($properties['styles'][$nLine])) $properties['styles'][$nLine] = [];
        $properties['styles'][$nLine][0] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getLineStyle($nLine)
    {
        $nLine = (int)$nLine;
        $properties = $this->getProperties();
        $nbLines = sizeof($properties['datas']);
        if ($nLine == 0 || $nbLines < $nLine) return false;

        return ((isset($properties['styles'][$nLine][0])) ? $properties['styles'][$nLine][0] : false);
    }

    public function clearLineStyle($nLine)
    {
        $nLine = (int)$nLine;
        $properties = $this->getProperties();
        $nbLines = sizeof($properties['datas']);
        if ($nLine == 0 || $nbLines < $nLine) return false;

        if (isset($properties['styles'][$nLine][0])) $properties['styles'][$nLine][0] = "";
        $this->setProperties($properties);
        return $this;
    }

    public function toggleLineStyle($nLine, $style = null)
    {
        $nLine = (int)$nLine;
        $properties = $this->getProperties();
        $nbLines = sizeof($properties['datas']);
        if ($nLine == 0 || $nbLines < $nLine) return false;

        $id = $properties['id'];
        $cStyle = (isset($properties['style'][$nLine][0])) ? $properties['style'][$nLine][0] : null;
        $oStyle = "";
        $session = new Container("styleTable_");
        if ($session->offsetExists('styleL' . $nLine)) $oStyle = $session->offsetGet('styleL' . $nLine);
        $session->offsetSet('styleL' . $nLine, $cStyle);
        $properties['style'][$nLine][0] = (!empty($style)) ? $style : $oStyle;
        $this->setProperties($properties);
        return $this;
    }

    public function addCellStyle($nCol, $nLine, $style)
    {
        $nCol = (int)$nCol;
        $nLine = (int)$nLine;
        $style = (string)$style;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        $nbLines = sizeof($properties['datas']);
        if ($nCol == 0 || $nbCols < $nCol) return false;
        if ($nLine == 0 || $nbLines < $nLine) return false;

        if (!isset($properties['styles'])) $properties['styles'] = [];
        if (!isset($properties['styles'][$nLine])) $properties['styles'][$nLine] = [];
        if (!isset($properties['styles'][$nLine][$nCol])) $properties['styles'][$nLine][$nCol] = "";
        $properties['styles'][$nLine][$nCol] .= " " . $style;
        $this->setProperties($properties);
        return $this;
    }

    public function setCellStyle($nCol, $nLine, $style)
    {
        $nCol = (int)$nCol;
        $nLine = (int)$nLine;
        $style = (string)$style;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        $nbLines = sizeof($properties['datas']);
        if ($nCol == 0 || $nbCols < $nCol) return false;
        if ($nLine == 0 || $nbLines < $nLine) return false;

        if (!isset($properties['styles'])) $properties['styles'] = [];
        if (!isset($properties['styles'][$nLine])) $properties['styles'][$nLine] = [];
        $properties['styles'][$nLine][$nCol] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getCellStyle($nCol, $nLine)
    {
        $nCol = (int)$nCol;
        $nLine = (int)$nLine;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        $nbLines = sizeof($properties['datas']);
        if ($nCol == 0 || $nbCols < $nCol) return false;
        if ($nLine == 0 || $nbLines < $nLine) return false;

        return ((isset($properties['styles'][$nLine][$nCol])) ? $properties['styles'][$nLine][$nCol] : false);
    }

    public function clearCellStyle($nCol, $nLine)
    {
        $nCol = (int)$nCol;
        $nLine = (int)$nLine;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        $nbLines = sizeof($properties['datas']);
        if ($nCol == 0 || $nbCols < $nCol) return false;
        if ($nLine == 0 || $nbLines < $nLine) return false;

        if (isset($properties['styles'][$nLine][$nCol])) $properties['styles'][$nLine][$nCol] = "";
        $this->setProperties($properties);
        return $this;
    }

    public function toggleCellStyle($nCol, $nLine, $style = null)
    {
        $nCol = (int)$nCol;
        $nLine = (int)$nLine;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        $nbLines = sizeof($properties['datas']);
        if ($nCol == 0 || $nbCols < $nCol) return false;
        if ($nLine == 0 || $nbLines < $nLine) return false;
        if (!isset($properties['style'][$nLine][$nCol])) return false;

        $id = $properties['id'];
        $cStyle = $properties['style'][$nLine][$nCol];
        $oStyle = "";
        $session = new Container("styleTable_" . $id);
        if ($session->offsetExists('styleC' . $nCol . 'L' . $nLine)) $oStyle = $session->offsetGet('styleC' . $nCol . 'L' . $nLine);
        $session->offsetSet('styleC' . $nCol . 'L' . $nLine, $cStyle);
        $properties['style'][$nLine][$nCol] = (!empty($style)) ? $style : $oStyle;
        $this->setProperties($properties);
        return $this;
    }

    public function evtTableClick($class, $method, $stopEvent = true)
    {
        $class                  = (string)$class;
        $method                 = (string)$method;
        $properties             = $this->getProperties();

        if(!isset($properties['event'])) $properties['event'] = [];
        if(!is_array($properties['event'])) $properties['event'] = [];
        if (!isset($properties['event'][0])) $properties['event'][0] = [];

        $properties['event'][0][0] = [];
        $properties['event'][0][0]['class'] = $class;
        $properties['event'][0][0]['method'] = $method;
        $properties['event'][0][0]['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';

        $this->setProperties($properties);
        return $this;
    }

    public function disTableClick()
    {
        $properties             = $this->getProperties();

        if (isset($properties['event'][0][0])) unset($properties['event'][0][0]);
        $this->setProperties($properties);
        return $this;
    }

    public function evtColClick($nCol, $class, $method, $stopEvent = true)
    {
        $class                  = (string)$class;
        $method                 = (string)$method;
        $properties             = $this->getProperties();
        $nbCols                 = sizeof($properties['cols']);
        if ($nCol > $nbCols || $nCol < 1) return false;

        if(!isset($properties['event'])) $properties['event'] = [];
        if(!is_array($properties['event'])) $properties['event'] = [];
        if (!isset($properties['event'][0])) $properties['event'][0] = [];

        $properties['event'][0][$nCol] = [];
        $properties['event'][0][$nCol]['class'] = $class;
        $properties['event'][0][$nCol]['method'] = $method;
        $properties['event'][0][$nCol]['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';

        $this->setProperties($properties);
        return $this;
    }

    public function disColClick($nCol)
    {
        $properties             = $this->getProperties();
        $nbCols                 = sizeof($properties['cols']);
        if ($nCol > $nbCols || $nCol < 1) return false;

        if (isset($properties['event'][0][$nCol])) unset($properties['event'][0][$nCol]);
        $this->setProperties($properties);
        return $this;
    }

    public function evtLineClick($nLine, $class, $method, $stopEvent = true)
    {
        $class                  = (string)$class;
        $method                 = (string)$method;
        $properties             = $this->getProperties();
        $nbLines                = sizeof($properties['datas']);
        if ($nLine > $nbLines || $nLine < 1) return false;

        if(!isset($properties['event'])) $properties['event'] = [];
        if(!is_array($properties['event'])) $properties['event'] = [];
        if (!isset($properties['event'][$nLine])) $properties['event'][$nLine] = [];

        $properties['event'][$nLine][0] = [];
        $properties['event'][$nLine][0]['class'] = $class;
        $properties['event'][$nLine][0]['method'] = $method;
        $properties['event'][$nLine][0]['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';

        $this->setProperties($properties);
        return $this;
    }

    public function disLineClick($nLine)
    {
        $properties             = $this->getProperties();
        $nbLines                = sizeof($properties['datas']);
        if ($nLine > $nbLines || $nLine < 1) return false;

        if (isset($properties['event'][$nLine][0])) unset($properties['event'][$nLine][0]);
        $this->setProperties($properties);
        return $this;
    }

    public function evtCellClick($nCol, $nLine, $class, $method, $stopEvent = true)
    {
        $class                  = (string)$class;
        $method                 = (string)$method;
        $properties             = $this->getProperties();
        $nbCols                 = sizeof($properties['cols']);
        if ($nCol > $nbCols || $nCol < 1) return false;
        $nbLines                = sizeof($properties['datas']);
        if ($nLine > $nbLines || $nLine < 1) return false;

        if(!isset($properties['event'])) $properties['event'] = [];
        if(!is_array($properties['event'])) $properties['event'] = [];
        if (!isset($properties['event'][$nLine])) $properties['event'][$nLine] = [];

        $properties['event'][$nLine][$nCol] = [];
        $properties['event'][$nLine][$nCol]['class'] = $class;
        $properties['event'][$nLine][$nCol]['method'] = $method;
        $properties['event'][$nLine][$nCol]['stopEvent'] = ($stopEvent) ? 'OUI' : 'NON';

        $this->setProperties($properties);
        return $this;
    }

    public function disCellClick($nCol, $nLine)
    {
        $properties             = $this->getProperties();
        $nbCols                 = sizeof($properties['cols']);
        if ($nCol > $nbCols || $nCol < 1) return false;
        $nbLines                = sizeof($properties['datas']);
        if ($nLine > $nbLines || $nLine < 1) return false;

        if (isset($properties['event'][$nLine][$nCol])) unset($properties['event'][$nLine][$nCol]);
        $this->setProperties($properties);
        return $this;
    }

    /*
     * méthode interne à la classe OObject
     */

    private function getTitlePosConstants()
    {
        $retour = [];
        if (empty($this->const_titlePos)) {
            $constants = $this->getConstants();
            foreach ($constants as $key => $constant) {
                $pos = strpos($key, 'TITLEPOS');
                if ($pos !== false) $retour[$key] = $constant;
            }
            $this->const_titlePos= $retour;
        } else {
            $retour = $this->const_titlePos;
        }
        return $retour;
    }

    public function showLine($nLine)
    {
        $nLine = (int) $nLine;
        $properties = $this->getProperties();
        $nbLines = sizeof($properties['datas']);
        if ($nLine < 1 || $nLine >$nbLines) return false;

        $properties['datas'][$nLine]['view'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function hideLine($nLine)
    {
        $nLine = (int) $nLine;
        $properties = $this->getProperties();
        $nbLines = sizeof($properties['datas']);
        if ($nLine < 1 || $nLine >$nbLines) return false;

        $properties['datas'][$nLine]['view'] = false;
        $this->setProperties($properties);
        return $this;
    }

    public function showCol($nCol)
    {
        $nCol = (int) $nCol;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nCol < 1 || $nCol > $nbCols) return false;

        $properties['cols'][$nCol]['view'] = true;
        $this->setProperties($properties);
        return $this;
    }

    public function hideCol($nCol)
    {
        $nCol = (int) $nCol;
        $properties = $this->getProperties();
        $nbCols = sizeof($properties['cols']);
        if ($nCol < 1 || $nCol > $nbCols) return false;

        $properties['cols'][$nCol]['view'] = false;
        $this->setProperties($properties);
        return $this;
    }
    public function findCellOnValue($value, $noCol = '', $noLine = '')
    {
        $crc = false;
        $crl = false;
        $crr = [];
        // cas noCol et noLine renseigné
        switch (true) {
            case (!empty($noCol) && !empty($noLine)) :
                $line = $this->getLine($noLine);
                $crr[] = ($value == $line[$noCol]) ? [$noCol, $noLine]: false;
                break;
            case (!empty($noCol) && empty($noLine)) :
                $lines = $this->getLines();
                $crl = false;
                foreach ($lines as $ind => $line) {
                    $crl = ($line[$noCol] == $value) ? $ind : false;
                    if ($crl !== false) {
                        $crr[] = [$noCol, $crl];
                    }
                }
                break;
            case (empty($noCol) && empty($noLine)) :
                $lines = $this->getLines();
                $crl   = false;
                foreach ($lines as $ind => $cols) {
                    $crc = false;
                    foreach ($cols as $jnd => $col) {
                        $crc = ($col == $value) ? $jnd : false;
                        if ($crc !== false) {
                            $crr[] = [$ind, $crl];
                        }
                    }
                }
                break;
            case (empty($noCol) && !empty($noLine)) :
                $line = $this->getLine($noLine);
                foreach ($line as $ind => $col) {
                    $crc = ($line[$noCol] == $value) ? $ind : false;
                    if ($crc !== false) {
                        $crr[] = [$crc, $noLine];
                    }
                }
                break;
        }
        return (!empty($crr)) ? $crr : false;
    }

    public function setOrderCols(array $colsOrder)
    {
        $orders = ['ASC', 'DESC'];
        $properties = $this->getProperties();
        $nbCols = count($properties['cols']);
        foreach ($colsOrder as $col => $order) {
            if ((int)$col < 1 || (int)$col > $nbCols) { return false; }
            $order = strtoupper($order);
            if (!in_array($order, $orders)) { return false; }
        }
        $properties['colsOrder'] = $colsOrder;
        $this->setProperties($properties);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrderCols()
    {
        $properties = $this->getProperties();
        return (array_key_exists('colsOrder', $properties) ? $properties['colsOrder'] : false);
    }

    public function setLinesStyles(array $styles = null)
    {
        $isOneDim = $this->arrayOneDim($styles);
        if ($isOneDim) {
            foreach ($styles as $noLine => $style) {
                $this->setLineStyle($noLine, $style);
            }
        }
        return $this;
    }

    public function setColsStyles(array $styles = null)
    {
        $isOneDim = $this->arrayOneDim($styles);
        $properties = $this->getProperties();
        $nbCols = count($properties['cols']);
        if ($isOneDim && count($styles) == $nbCols) {
            foreach ($styles as $noCol => $style) {
                $this->setcolStyle($noCol, $style);
            }
        }
        return $this;
    }

    public function setCellsStyles(array $style = null)
    {
        $properties = $this->getProperties();
        $nbCols     = count($properties['cols']);
        $nbLines    = count($properties['datas']);
        $dim        = $this->arrayMaxDim($style);
        if ($dim == [$nbLines,$nbCols]) {
            for ($ind=1; $ind<=$nbLines; $ind++) {
                for ($jnd=1; $jnd<=$nbCols; $jnd++) {
                    $this->setCell($ind, $jnd, $style[$ind][$jnd]);
                }
            }
        }
    }


    public function clearAllStyles()
    {
        $properties = $this->getProperties();
        $properties['styles'] = [];
        $this->setProperties($properties);
        return $this;
    }

    public function clearColsStyles()
    {
        $properties = $this->getProperties();
        $nbCols = count($properties['cols']);
        for ($ind=1; $ind<=$nbCols; $ind++) {
            $properties['style'][0][$ind] = '';
        }
        $this->setProperties($properties);
        return $this;
    }

    public function clearLinesStyles()
    {
        $properties = $this->getProperties();
        $nbLines = count($properties['datas']);
        for ($ind=1; $ind<=$nbLines; $ind++) {
            $properties['style'][$ind][0] = '';
        }
        $this->setProperties($properties);
        return $this;
    }

    public function clearCellsStyles()
    {
        $properties = $this->getProperties();
        $nbCols = count($properties['cols']);
        $nbLines = count($properties['datas']);
        for ($ind = 1; $ind<=$nbLines; $ind++) {
            for ($jnd = 1; $jnd<=$nbCols; $jnd++) {
                $properties['style'][$ind][$jnd] = '';
            }
        }
        $this->setProperties($properties);
        return $this;
    }


    private function sortDatas()
    {
        $properties = $this->getProperties();
        $orderCols  = $this->getOrderCols();
        if (!empty($orderCols)) {
            $datas  = $properties['datas'];
            unset($datas[0]);
            $styles = $properties['styles'];
            unset($styles[0]);
            foreach ($datas as $noLine => $data) {
                $datas[$noLine][$this->getId().'NoLine'] = $noLine;
            }

            $callOrder  = "array_multisort(";
            foreach ($orderCols as $col => $order) {
                $callOrder .= ' $data['.$col.'], SORT_'.strtoupper($order).', SORT_REGULAR, ';
            }
            $callOrder .= ' $datas);';
            eval($callOrder);

            $lines = [];
            $sLines = [];
            $sCells = [];
            $this->clearCellsStyles();
            $this->clearLinesStyles();
            foreach ($datas as $data) {
                $noLine = $data[$this->getId().'NoLine'];
                unset($data[$this->getId().'NoLine']);
                $lines[] = $data;
                $sLines[] = $styles[$noLine][0];
                unset($styles[$noLine][0]);
                $sCells[] = $styles[$noLine];
            }
            $this->setLines($lines);
            $this->setLinesStyles($sLines);
            $this->setCellsStyles($sCells);
        }
    }

    private function arrayOneDim(array $ar)
    {
        $ret = true;
        foreach ($ar as $item) {
            if (is_array($item)) { $ret = (false or $ret); }
        }
        return $ret;
    }

    private function arrayMaxDim(array $ar)
    {
        $cols  = 0;
        $lines = count($ar);
        foreach ($ar as $item) {
            $tmpCols = count($item);
            if ($tmpCols > $cols) { $cols = $tmpCols; }
        }
        return [$lines, $cols];
    }

    private function updateEvent($event, $action)
    {
        $properties = $this->getProperties();
        $evts       = $properties['events'];
        switch ($action) {
            case 'add':
                if (!array_key_exists($event, $evts)) { $evts[$event] = 0; }
                $evts[$event]++;
                break;
            case 'sub':
                if (!array_key_exists($event, $evts)) { $evts[$event]--; }
                break;
        }
        foreach ($evts as $evt => $count) {
            if ($count === 0) { unset($evts[$evt]); }
        }
        $properties['events'] = $evts;
        $this->setProperties($properties);
        return $this;
    }

    public function dispatchEvents($sm, $params)
    {
        $object = OObject::buildObject($params['id']);
        $properties = $object->getProperties();
        $events     = $properties['event'];
        $lno        = (int) substr($params['value'], 1);
        $pos        = strpos($params['value'], 'C');
        $cno        = (int) substr($params['value'], $pos + 1);
        $ret        = [];

        $execEvt    = $events[$lno][$cno];
        $stopEvent  = false;
        if (!empty($execEvt)) {
            if (!empty($execEvt)) {
                foreach ($execEvt as $exec) {
                    $callObj = new $exec['class']();
                    $ret[] = array_merge($ret, call_user_func_array([$callObj, $exec['method']], [$sm, [$lno, $cno]]));
                    $stopEvent = $execEvt['stopEvent'];
                }
            }
        }

        if ($lno !=0 && !$stopEvent) {
            $execEvt    = $events[0][$cno];
            if (!empty($execEvt)) {
                foreach ($execEvt as $exec) {
                    $callObj = new $exec['class']();
                    $ret[] = array_merge($ret, call_user_func_array([$callObj, $exec['method']], [$sm, [$lno, $cno]]));
                    $stopEvent = $execEvt['stopEvent'];
                }
            }
        }
        if ($cno !=0 && !$stopEvent) {
            $execEvt    = $events[$lno][0];
            if (!empty($execEvt)) {
                foreach ($execEvt as $exec) {
                    $callObj = new $exec['class']();
                    $ret[] = array_merge($ret, call_user_func_array([$callObj, $exec['method']], [$sm, [$lno, $cno]]));
                    $stopEvent = $execEvt['stopEvent'];
                }
            }
        }
        if ($lno !=0 && $cno != 0 && !$stopEvent) {
            $execEvt    = $events[0][0];
            if (!empty($execEvt)) {
                foreach ($execEvt as $exec) {
                    $callObj = new $exec['class']();
                    $ret[] = array_merge($ret, call_user_func_array([$callObj, $exec['method']], [$sm, [$lno, $cno]]));
                }
            }
        }
        return $ret;
    }
}