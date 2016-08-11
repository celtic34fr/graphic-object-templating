Méthode de l'objet ODTable
--------------------------

Il contient les méthodes communes aux objets OObject_  et ODContained_ avant d'avoir des méthodes spéfifiques :

+--------------------------------------+--------------------------------------------------------------------------------------+
| Méthode                              + Fonctionnalité                                                                       |
+======================================+======================================================================================+
|setTitle()          	                 | affecte un titre ou légende au tableau                                               |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getTitle()	                           | restitué le titre ou légende du tableau                                              |
+--------------------------------------+--------------------------------------------------------------------------------------+
|initColsHead(array())	               | affecte au colonnes leurs titre ou entêtes                                           |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getColsHead()	                       | restitue les titres des colonnes                                                     |
+--------------------------------------+--------------------------------------------------------------------------------------+
|addLine(array())                      | ajoute une ligne de données au tableau                                               |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setLine(nLine, array())	             | ajout ou met à jour la ligne nLine dans le tableau                                   |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setLines(array())	                   | remplace l'ensermble des lignes du tableau avec le contenu de array()                |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getLine(nLine)	                       | restitue le tableau de valeur formant la ligne nLine                                 |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getLines()	                           | restitue le tableau des tableau de valeurs formant les lignes du tableau             |
+--------------------------------------+--------------------------------------------------------------------------------------+
|removeLine(nLine)	                   | supprime la ligne nLine du tableau (les lignes nLine + 1 à fin deviennent nLine à    |
|                                      | fin -1)                                                                              |
+--------------------------------------+--------------------------------------------------------------------------------------+
|removeLines()	                       | supprime toutes les lignes de données du tableau (par les entêtes de colonnes)       |
+--------------------------------------+--------------------------------------------------------------------------------------+
|clearTable()	                         | supprime tout le contenu du tableau, entête comprise                                 |
+--------------------------------------+--------------------------------------------------------------------------------------+
|addColTitle(title[, array()[, nCol]]) | ajoute une colone en fin de tableau (niveau entête) si nCol est indiqué, insersion en|
|                                      | colone nCol                                                                          |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setColValues(nCol, array())           | met à jour les valeurs contenues dans la colonne nCol avec le tableau array()        |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getCol(nCol)	                         | restitue le contenu de la colonne nCol sous forme d'un array()                       |
+--------------------------------------+--------------------------------------------------------------------------------------+
|removeCol(nCol)	                     | supprime la colonne nCol en valeurs et entête                                        |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setCell(nCol,nLine, value)            | affecte à la colone nCol, ligne nLine, le contenu value                              |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getCell(nCol,nLine)                   | restitue le cotenu de la cellule colone nCol, ligne nLine                            |
+--------------------------------------+--------------------------------------------------------------------------------------+
|addTableStyle(style)	                 | ajout le contenu de la chaîne style au style actuel du tableau                       |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setTableStyle(style)	                 | affecte au style du tableau le contenu de la chaîne style                            |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getTableStyle()                       | restitue le style actuel du tableau                                                  |
+--------------------------------------+--------------------------------------------------------------------------------------+
|clearTableStyle()	                   | supprime tout style au tableau (niveau tableau, pas lignes ou colonnes)              |
+--------------------------------------+--------------------------------------------------------------------------------------+
|toggleTableStyle([style])             | permute le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne|
|                                      | style avec l'actuel qu'il sauvegarde alors                                           |
+--------------------------------------+--------------------------------------------------------------------------------------+
|addColStyle(nCol, style)	             | ajout le contenu de la chaîne style au style actuel de la colonne nCol               |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setColStyle(nCol, style)	             | affecte au style de la colonne nCol le contenu de la chaîne style                    |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getColStyle(nCol)	                   | restitue le style actuel de la colonne nCol                                          |
+--------------------------------------+--------------------------------------------------------------------------------------+
|clearColStyle(nCol)	                 | supprime tout style à la colonne nCol                                                |
+--------------------------------------+--------------------------------------------------------------------------------------+
|toggleColStyle(nCol[, style])	       | permute le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne|
|                                      | style avec l'actuel qu'il sauvegarde alors                                           |
+--------------------------------------+--------------------------------------------------------------------------------------+
|addLineStyle(nLine, style)	           | ajout le contenu de la chaîne style au style actuel de la ligne nLine                |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setLineStyle(nLine, style)	           | affecte au style de la ligne Nline le contenu de la chaîne style                     |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getLineStyle(nLine)	                 | restitue le style actuel de la ligne nLine                                           |
+--------------------------------------+--------------------------------------------------------------------------------------+
|clearLineStyle(nLine)	               | supprime tout style à la ligne nLine                                                 |
+--------------------------------------+--------------------------------------------------------------------------------------+
|toggleLineStyle(nLine[, style])       | permute le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne|
|                                      | style avec l'actuel qu'il sauvegarde alors                                           |
+--------------------------------------+--------------------------------------------------------------------------------------+
|addCellStyle(nCol, nLine, style)      | ajout le contenu de la chaîne style au style actuel de la cellule nCol, nLine        |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setCellStyle(nCol, nLine, style)      | affecte au style de la ligne Nline le contenu de la cellule nCol, nLine              |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getCellStyle(nCol, nLine)	           | restitue le style actuel de la cellule nCol, nLine                                   |
+--------------------------------------+--------------------------------------------------------------------------------------+
|clearCellStyle(nCol, nLine)	         | supprime tout style à la cellule nCol, nLine                                         |
+--------------------------------------+--------------------------------------------------------------------------------------+
|toggleCellStyle(nCol, nLine[, style]) | permute le style actuel avec le style sauvegardé (si existe), ou celui dans la chaîne|
|                                      | style avec l'actuel qu'il sauvegarde alors                                           |
+--------------------------------------+--------------------------------------------------------------------------------------+
|evtColClick(nCol, callback)	         | positionnement un événement onClick sur la colonne nCol, et demande l'exécution de   |
|                                      | callback                                                                             |
+--------------------------------------+--------------------------------------------------------------------------------------+
|disColClick(nCol)	                   | déactive l’événement onClick sur la colonne nCol                                     |
+--------------------------------------+--------------------------------------------------------------------------------------+
|evtLineClick(nLine, callback)	       | positionnement un évènement onClick sur la ligne nLine, et demande l'exécution de    |
|                                      | callback                                                                             |
+--------------------------------------+--------------------------------------------------------------------------------------+
|disLineClick(nLine)	                 | déactive l’événement onClick sur la ligne nLine                                      |
+--------------------------------------+--------------------------------------------------------------------------------------+
|evtCellClick(nCol, nLine, callback)   | positionnement un évènement onClick sur la cellule nCol, nLine, et demande           |
|                                      | l'exécution de callback                                                              |
+--------------------------------------+--------------------------------------------------------------------------------------+
|disCellClick(nCol, nLine)             | déactive l’événement onClick sur la cellule nCol, nLine                              |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getSelectedCols()           	         | restitue sous forme d'un array() l'ensemble des colonnes sélectionnées               |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setSelectedCol(nCol)     	           | sélectionne la colonne nCol pour l'affichage marqué de cette dernière                |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setSelectedCols(array())              | sélection un groupe de colonne déscrite dans array() par leurs numéros pour          |
|                                      | l'affichage marqué de ces dernières                                                  |
+--------------------------------------+--------------------------------------------------------------------------------------+
|unselectCol(nCol)                     | désélectionne la colonne nCol (si elle l'est) pour affichage non marqué de cette     |
|                                      | dernières                                                                            |
+--------------------------------------+--------------------------------------------------------------------------------------+
|unselectAllCols()       	             | désélectionne l'ensemble des colonnes sélectionnées (retour total désélectionné)     |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getSelectedLines()                    | restitue sur forme d'un array l'ensemble des lignes sélectionnées                    |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setSelectedLine(nLine)           	   | sélectionne la ligne nLine pour affichage marqué de cette dernière                   |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setSelectedLines(array())	           | sélectionne un groupe de ligne décrite dans array() par leurs numéros pour affichage |
|                                      | marqué de ces dernières                                                              |
+--------------------------------------+--------------------------------------------------------------------------------------+
|unselectLine(nLine)	                 | désélectionne la ligne nLine pour affichage non marqsué de cette dernière            |
+--------------------------------------+--------------------------------------------------------------------------------------+
|unselectAllLines()	                   | désélectionne l'ensemble des lignes sélectionnées (retour total désélectionné)       |
+--------------------------------------+--------------------------------------------------------------------------------------+
|getSelectedCells()    	               | restitue sur forme d'un array l'ensemble des cellules sélectionnées                  |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setSelectedCell(nCol, nLine) 	       | sélectionne la cellule nCol, nLine pour affichage marqué de cette dernière           |
+--------------------------------------+--------------------------------------------------------------------------------------+
|setSelectedCells(array())	           | sélectionne un groupe de cellules descrites dans array() par leus coordonnées dans le|
|                                      | tableau pour affichage marqué de ces dernières                                       |
+--------------------------------------+--------------------------------------------------------------------------------------+
|unselectCell(nCol, nLine)    	       | désélectionne la cellule nCol, nLine pour affichage non marqué de cette dernière     |
+--------------------------------------+--------------------------------------------------------------------------------------+
|unselectAllCells()          	         | désélectionne l'ensemble des cellules sélectionnées (retour total désélectionné)     |
+--------------------------------------+--------------------------------------------------------------------------------------+
|showLine(nLine)                       | précise que la ligne nLine sera affichée                                             |
+--------------------------------------+--------------------------------------------------------------------------------------+
|hideLine(nLine)                       | précise que la ligne nLine ne sera pas affichée                                      |
+--------------------------------------+--------------------------------------------------------------------------------------+
|showCol(nCol, boolean)                | précise que la colonne nCol sera affichée                                            |
+--------------------------------------+--------------------------------------------------------------------------------------+
|hideCol(nCol)                         | précise que la Colonne nCol ne sera pas affichée                                     |
+--------------------------------------+--------------------------------------------------------------------------------------+

.. _OObject: OObject.rst
.. _ODContained: ODContained.rst
