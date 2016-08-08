<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 08/08/16
 * Time: 13:18
 */

namespace GraphicObjectTemplating\Objects\ODContained;

use GraphicObjectTemplating\Objects\ODContained;

/**
 * Class ODTable
 * @package GraphicObjectTemplating\Objects\ODContained
 *
 * !setTitle()                             : affecte un titre ou légende au tableau
 * !getTitle()                             : restitué le titre ou légende du tableau
 * !initCols(array())                      : affecte au colonnes leurs titre ou entêtes
 * !addLine(array())                       : ajoute une ligne de données au tableau
 * !setLine(nLine, array())                : ajout ou met à jour la ligne nLine dans le tableau
 * !setLines(array())                      : remplace l'ensermble des lignes du tableau avec le contenu de array()
 * !getLine(nLine)                         : restitue le tableau de valeur formant la ligne nLine
 * !getLines()                             : restitue le tableau des tableau de valeurs formant les lignes du tableau
 * !removeLine(nLine)                      : supprime la ligne nLine du tableau (les lignes nLine + 1 à fin deviennent nLine à fin -1)
 * !removeLines()                          : supprime toutes les lignes de données du tableau (par les entêtes de colonnes)
 * !clearTable()                           : supprime tout le contenu du tableau, entête comprise
 * addColTitle(title[, array(),[, nCol]]) : ajoute une colone en fin de tableau (niveau entête) si nCol est indiqué, insersion en colone nCol
 * setColValues(nCol, array())            : met à jour les valeuyrs contenues dans la colonne nCol avec le tabelau array()
 * getCol(nCol)                           : restitue le contenu de la colonne nCol sous forme d'un array()
 * removeCol(nCol)                        : supprimme la colonne nCol en valeurs et entête
 * !addTableStyle(style)                   : ajout le contenu de la chaîne style au style actuel du tableau
 * !setTableStyle(style)                   : affecte au style du tableau le contenu de la chaîne style
 * !getTableStyle()                        : restitue le style actuel du tableau
 * !clearTableStyle()                      : supprime tout style au tableau (niveau tableau, pas lignes ou colonnes)
 * toggleTableStyle([style])              : permutte le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne style avec l'actuel qu'il sauvegarde alors
 * !addColStyle(nCol, style)               : ajout le contenu de la chaîne style au style actuel de la colonne nCol
 * !setColStyle(nCol, style)               : affecte au style de la colonne nCol le contenu de la chaîne style
 * !getColStyle(nCol)                      : restitue le style actuel de la colonne nCol
 * clearColStyle(nCol)                    : supprime tout style à la colonne nCol
 * toggleColStyle(nCol[, style])          : permutte le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne style avec l'actuel qu'il sauvegarde alors
 * !addLineStyle(nLine, style)             : ajout le contenu de la chaîne style au style actuel de la ligne nLine
 * !setLineStyle(nLine, style)             : affecte au style de la ligne Nline le contenu de la chaîne style
 * !getLineStyle(nLine)                    : restitue le style actuel de la ligne nLine
 * clearLineStyle(nLine)                  : supprime tout style à la ligne nLine
 * toggleLineStyle(nLine[, style])        : permutte le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne style avec l'actuel qu'il sauvegarde alors
 * !addCellStyle(nCol, nLine, style)       : ajout le contenu de la chaîne style au style actuel de la cellule nCol, nLine
 * !setCellStyle(nCol, nLine, style)       : affecte au style de la ligne Nline le contenu de la cellule nCol, nLine
 * !getCellStyle(nCol, nLine)              : restitue le style actuel de la cellule nCol, nLine
 * clearCellStyle(nCol, nLine)            : supprime tout style à la cellule nCol, nLine
 * toggleCellStyle(nCol, nLine[, style])  : permutte le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne style avec l'actuel qu'il sauvegarde alors
 * evtColClick(nCol, callback)            : positionnement un évènement onClick sur la colonne nCol, et demande l'exécution de callback
 * disColClick(nCol)                      : déactive l'évènement onClick sur la colonne nCol
 * evtLineClick(nLine, callback)          : positionnement un évènement onClick sur la ligne nLine, et demande l'exécution de callback
 * disLineClick(nLine)                    : déactive l'évènement onClick sur la ligne nLine
 * evtCellClick(nCol, nLine, callback)    : positionnement un évènement onClick sur la cellule nCol, nLine, et demande l'exécution de callback
 * disCellClick(nCol, nLine)              : déactive l'évènement onClick sur la cellule nCol, nLine
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
 */
class ODTable extends ODContained
{
    public function __construct($id)
    {
        $parent = parent::__construct($id, "oobject/odcontained/odtable/odtable.config.phtml");
        $this->properties = $parent->properties;
        $this->setDisplay();
        $width = $this->getWidthBT();
        if (!is_array($width) || empty($width)) $this->setWidthBT(12);
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
        $properties = $this->getProperties();
        return (array_key_exists('title', $properties) ? $properties['title'] : false);
    }

    public function initCols(array $cols = null) {
        if (empty($cols)) return false;
        $properties = $this->getProperties();
        $properties['cols'] = $cols;
        $this->setProperties($properties);
        return $this;
    }

    public function addLine(array $line = null)
    {
        if (empty($line)) return false;
        $properties = $this->getProperties();
        $nbCols     = sizeof($properties['cols']);
        if ($nbCols != sizeof($line)) return false;

        $properties['datas'][] = $line;
        $this->setProperties($properties);
        return (key(end($properties['datas'])));
    }

    public function setLine($nLine, array $line = null)
    {
        $properties = $this->getProperties();
        $nbCols     = sizeof($properties['cols']);
        $nbLines    = sizeof($properties['datas']);
        if ($nbLines == 0) return false;
        if ($nLine >= $nbLines) return false;
        if ($nbCols != sizeof($line)) return false;

        $properties['datas'][$nLine] = $line;
        $this->setProperties($properties);
        return $this;
    }

    public function setLines(array $lines = null)
    {
        if (empty($lines)) return false;
        $properties = $this->getProperties();
        $nbCols     = sizeof($properties['cols']);
        // vérification des lignes de lines qui doivent avoir le bon nombre de colonnes
        foreach ($lines as $line) {
            if ($nbCols != sizeof($line)) return false;
        }

        $properties['datas'] = $lines;
        $this->setProperties($properties);
        return $this;
    }

    public function getLine($nLine)
    {
        $properties = $this->getProperties();
        $nbLines    = sizeof($properties['datas']);
        if ($nbLines == 0) return false;
        if ($nLine >= $nbLines) return false;

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
        $nbLines    = sizeof($properties['datas']);
        if ($nbLines == 0) return false;
        if ($nLine >= $nbLines) return false;

        unset($properties['datas'][$nLine]);
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
        $properties['cols'] = [];
        $properties['datas'] = [];
        $properties['styles'] = array(array());
        $this->setProperties($properties);
        return $this;
    }

    public function addTableStyle($style){
        $style = (string) $style;
        $properties = $this->getProperties();

        if (!isset($properties['styles'])) $properties['styles'] = array(array());
        if (!isset($properties['styles'][0][0])) $properties['styles'][0][0] = "";
        $properties['styles'][0][0] .= " " .$style;
        $this->setProperties($properties);
        return $this;
    }

    public function setTableStyle($style) {
        $style = (string) $style;
        $properties = $this->getProperties();

        if (!isset($properties['styles'])) $properties['styles'] = array(array());
        $properties['styles'][0][0] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getTableStyle() {
        $properties = $this->getProperties();
        return ((isset($properties['styles'][0][0])) ? $properties['styles'][0][0] : false) ;
    }

    public function clearTableStyle()
    {
        $properties = $this->getProperties();
        if (isset($properties['styles'][0][0])) $properties['styles'][0][0] = null;
    }

    public function addColStyle($nCol, $style) {
        $nCol                              = (int) $nCol;
        $style                             = (string) $style;
        $properties                        = $this->getProperties();
        if (!isset($properties['styles'])) $properties['styles'] = array(array());
        if (!isset($properties['styles'][0][$nCol])) $properties['styles'][0][$nCol] = "";
        $properties['styles'][0][$nCol] .= " " .$style;
        $this->setProperties($properties);
        return $this;
    }

    public function setColStyle($nCol, $style) {
        $nCol                             = (int) $nCol;
        $style                            = (string) $style;
        $properties                       = $this->getProperties();
        if (!isset($properties['styles'])) $properties['styles'] = array(array());
        $properties['styles'][0][$nCol] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getColStyle($nCol) {
        $nCol  = (int) $nCol;
        $properties                        = $this->getProperties();
        return ((isset($properties['styles'][0][$nCol])) ? $properties['styles'][0][$nCol] : false) ;
    }

    public function addLineStyle($nLine, $style) {
        $nLine = (int) $nLine;
        $style = (string) $style;
        $properties                         = $this->getProperties();
        if (!isset($properties['styles'])) $properties['styles'] = array(array());
        if (!isset($properties['styles'][$nLine][0])) $properties['styles'][$nLine][0] = "";
        $properties['styles'][$nLine][0] .= " " .$style;
        $this->setProperties($properties);
        return $this;
    }

    public function setLineStyle($nLine, $style) {
        $nLine = (int) $nLine;
        $style = (string) $style;
        $properties                        = $this->getProperties();
        if (!isset($properties['styles'])) $properties['styles'] = array(array());
        $properties['styles'][0][$nLine] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getLineStyle($nLine) {
        $nLine = (int) $nLine;
        $properties                         = $this->getProperties();
        return ((isset($properties['styles'][$nLine][0])) ? $properties['styles'][$nLine][0] : false) ;
    }

    public function addCellStyle($nCol, $nLine, $style) {
        $nCol  = (int) $nCol;
        $nLine = (int) $nLine;
        $style = (string) $style;
        $properties                               = $this->getProperties();
        if (!isset($properties['styles'])) $properties['styles'] = array(array());
        if (!isset($properties['styles'][$nLine][$nCol])) $properties['styles'][$nLine][$nCol] = "";
        $properties['styles'][$nLine][$nCol] .= " " .$style;
        $this->setProperties($properties);
        return $this;
    }

    public function setCellStyle($nCol, $nLine, $style) {
        $nCol  = (int) $nCol;
        $nLine = (int) $nLine;
        $style = (string) $style;
        $properties                              = $this->getProperties();
        if (!isset($properties['styles'])) $properties['styles'] = array(array());
        $properties['styles'][$nLine][$nCol] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getCellStyle($nCol, $nLine) {
        $nCol  = (int) $nCol;
        $nLine = (int) $nLine;
        $properties                               = $this->getProperties();
        return ((isset($properties['styles'][$nLine][$nCol])) ? $properties['styles'][$numLine][$nCol] : false) ;
    }

}