<?php
namespace GraphicObjectTemplating\Service;

use GraphicObjectTemplating\Objects\OObject;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Model\ViewModel;

class GotServices
{
	private $_sm;
	private $_vm;
	
	public function __construct(ServiceLocatorInterface $sm)
	{
		$this->_sm = $sm;
		$this->_vm = $sm->get('ViewManager');
		return $this;
	}
	
	public function render($object)
	{
		$twigRender = $this->_sm->get('ZfcTwigRenderer');
		$html       = new ViewModel();
        $properties = $object->getProperties();
        $template   = $properties['template'];
        $allow      = 'ALLOW';

        switch($properties['typeObj']) {
            case 'odcontent' :
                $html->setTemplate($template);
                $html->setVariable('objet', $properties);
                break;
            case 'oscontainer':
                $content  = "";
                $children = $object->getChildren();
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
		$renduHtml = $twigRender->render($html);
		return $renduHtml;
	}
}
