<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 28/02/16
 * Time: 18:00
 */

namespace GraphicObjectTemplating\Controller;

use Application\Controller\IndexController;
use GraphicObjectTemplating\Objects\OObject;
use Zend\Config\Config;
use Zend\Http\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class GOTController extends AbstractActionController
{
    public function indexAction()
    {
        $view = new ViewModel();
        return $view;
    }

    public function callbackAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $paramsPost = $request->getPost();
            $params     = [];
            foreach ($paramsPost as $cle => $param) {
                if (substr($param, 0, 1) == "'") $param = substr($param, 1);
                if (substr($param, strlen($param) - 1, 1) == "'")
                    $param = substr($param, 0, strlen($param) - 1);
                $$cle = $param;
                $params[$cle] = $param;
            }

            if (isset($callback) && !empty($callback)) {
                // formatable namespace controleur + nom méthode(Action) appelé
                $pos = strpos($callback, '/');
                $module = ucfirst(substr($callback, 0, $pos));
                $callback = substr($callback, $pos + 1);
                $pos = strpos($callback, '/');
                $controller = ucfirst(substr($callback, 0, $pos));
                $method = substr($callback, $pos + 1);

                if ($module != "GraphicObjectTemplating") {
                    $nomController = $module."/Controller/".$controller."Controller";
                } else {
                    $nomController = $module."/Objects/OMComposed/".$controller;
                }
                $nomController = str_replace("/", chr(92), $nomController);

                if ($module != "GraphicObjectTemplating") {
                    $object = new $nomController;
                } else {
                    $object = new $nomController("dummy");
                }

                // traitement en cas de formulaire
                if (isset($form) && !empty($form)) {
                    $formDatas = [];
                    $form      = substr($form, 1, strlen($form) - 2);
                    $datas     = explode("|", $form);

                    foreach ($datas as $data) {
                        if (!empty($data)) {
                            $data                = explode("§", $data);
                            foreach ($data as $item) {
                                switch (true) {
                                    case (strpos($item, 'id=') !== false):
                                        $id = substr($item, 3);
                                        break;
                                    case (strpos($item, 'value=') !== false):
                                        $value = substr($item, 6);
                                        if (substr($value, 0, 1) == "'") $value = substr($value, 1);
                                        if (substr($value, strlen($value) - 1, 1) == "'")
                                            $value = substr($value, 0, strlen($value) - 1);                                        break;
                                }
                                if (isset($id) && isset($value)) {
                                    $obj = OObject::buildObject($id);
                                    $obj->convertValue($value);
                                    $value = $obj->getConverted();
                                    $formDatas[$id] = $value;
                                }
                            }
                        }
                    }
                    $params['form'] = $formDatas;
                }

                // appel preprement dit namespace controlleur + méthode(Action)
                if ($module != "GraphicObjectTemplaing") {
                    $result = call_user_func_array(array($object, $method),
                        array(
                            'sl' => $this->getServiceLocator(),
                            $params
                        ));
                }
                $data = new JsonModel($result);

                $response = $this->getResponse();
                $response->setStatusCode(200);
                $response->setContent($data->serialize());
                return $response;
            }
        }
    }

    static public function gotRender($objet, $sl)
    {
        $html       = new ViewModel();
        $properties = $objet->getProperties();
        $template   = $properties['template'];
        $allow      = 'ALLOW';

        switch($properties['typeObj']) {
            case 'odcontent' :
                $html->setTemplate($template);
                $html->setVariable('objet', $properties);
                break;
            case 'omcomposed' :
                $children = $objet->getChildren();
                if (!empty($children)) {
                    switch ($objet->getObject()){
                        case "omtable":
                            if (empty($properties['loadDatas'])) {
                                $start = intval($objet->getStart());
                                if (empty($start)) $start = 0;
                                $length = $objet->getLength();
                                if (empty($length)) $length = 10;
                                if (is_array($length)) $length = $length[0];

                                $dataLines = $objet->getDataLines();
                                $maxRow  = sizeof($properties['datas']);
                                $tabArray  = [];
                                for ($i =0; $i < $length; $i++) {
                                    if (($start + $i) < $maxRow) {
                                        $tabArray[$i + 1] = $dataLines[$start + $i + 1];
                                    } else {
                                        break;
                                    }
                                }
                                $objet->table->setLines($tabArray);
                                $html->setVariable('table', $objet->table);
                                $noPage = $start / $length + 0.5;
                                $noPage = round($noPage);
                                $objet->buildNavBar($noPage, sizeof($dataLines), $sl);
                            }
                            break;
                    }
                    foreach ($children as $key => $child) {
                        $child = OObject::buildObject($child->getId());
                        $name = substr($child->getId(), strlen($properties['id']) + 1);
                        $html->setVariable($name, $child);
                    }
                    $html->setTemplate($template);
                    $html->setVariable('objet', $properties);
                }
                break;
            case 'oscontainer':
                $content  = "";
                $children = $objet->getChildren();
                if (!empty($children)) {
                    foreach ($children as $key => $child) {
                        $child = OObject::buildObject($child->getId());

                        $rendu    = self::__invoke($child);
                        $content .= $rendu;
                    }
                }
                $html->setTemplate($template);
                $html->setVariable('objet', $properties);
                $html->setVariable('content', $content);
                break;
        }

        $renduHtml = $sl->get('ZfcTwigRenderer')->render($html);
        return $renduHtml;
    }
}