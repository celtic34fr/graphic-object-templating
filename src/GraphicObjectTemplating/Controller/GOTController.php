<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 28/02/16
 * Time: 18:00
 */

namespace GraphicObjectTemplating\Controller;

use GraphicObjectTemplating\Objects\OObject;
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

                $nomController = $module."/Controller/".$controller."Controller";
                $nomController = str_replace("/", chr(92), $nomController);
                $object = new $nomController;

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
                $data = new JsonModel($result);

                $response = $this->getResponse();
                $response->setStatusCode(200);
                $response->setContent($data->serialize());
                return $response;
            }
        }
    }

    static public function gotRender($objet, $twig)
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
            case 'oscontainer':
                $content  = "";
                $children = $objet->getChildren();
                if (!empty($children)) {
                    foreach ($children as $key => $child) {
                        $child = OObject::buildObject($child->getId());

                        $rendu    = self::gotRender($child);
                        $content .= $rendu;
                    }
                }
                $html->setTemplate($template);
                $html->setVariable('objet', $properties);
                $html->setVariable('content', $content);
                break;
        }

        $renduHtml = $twig->render($html);
        return $renduHtml;
    }
}