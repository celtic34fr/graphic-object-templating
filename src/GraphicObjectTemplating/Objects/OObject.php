<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 25/02/16
 * Time: 10:38
 */

namespace GraphicObjectTemplating\Objects;

use Zend\Config\Config;
use Zend\Server\Reflection\ReflectionClass;
use Zend\Session\Container;
use Zend\View\Model\ViewModel;

/**
 * Class OObject
 * @package GraphicObjectTemplating\Objects
 *
 * properties   : tableau (objet Config()) stockant l'ensemble des attributs de l'objet
 *
 * id           : identifiant unique de l'objet
 * template     : chaîne de caractère donnant le nom et chemin d'accès au template de l'objet
 * display      : indique la notion d'afficahge de l'objet
 *      none            : l'objet est invisible
 *      block           : affichage en mode bloc
 *      inline          : affichage en mode 'inline' (insersion de ligne)
 *      inline-block    : affiche en mode mixte inline & block
 * style        : style spécifique à l'objet
 * classes      : classe CSS affectée(s) à lobjet
 * infoBulle    : texte explicatif qui apparaît au survol de l'objet
 * iBPosition   : position de l'infoBulle (gauche, dessous, droite, dessus)
 * resources    : liste en 2 tableau des fichies CSS et JS utilies à l'objet
 * aclReference : resource ACL associé à l'objet
 */
class OObject
{

    const DISPLAY_NONE    = 'none';
    const DISPLAY_BLOCK   = 'block';
    const DISPLAY_INLINE  = 'inline';
    const DISPLAY_INBLOCK = 'inline-block';

    const TOOLTIP_LEFT    = "left";
    const TOOLTIP_BOTTOM  = "bottom";
    const TOOLTIP_RIGHT   = "right";
    const TOOLTIP_TOP     = "top";

    protected $id;
    protected $properties;

    public function __construct($id, $adrProperties)
    {
        $this->initProperties($id, $adrProperties);
    }

    public function initProperties($id, $arrayData)
    {
        $properties = include(__DIR__ ."/../../../view/graphic-object-templating/" .$arrayData);
        $properties['id']   = $id;
        $properties['name'] = $id;
        $templateName  = 'graphic-object-templating/oobject/'.$properties['typeObj'];
        $templateName .= '/'.$properties['object'].'/'.$properties['template'];
        $properties['template'] = $templateName;

        $objName = "GraphicObjectTemplating/Objects/";
        $objName .= strtoupper(substr($properties['typeObj'], 0, 3));
        $objName .= strtolower(substr($properties['typeObj'], 3)).'/';
        $objName .= strtoupper(substr($properties['object'], 0, 3));
        $objName .= strtolower(substr($properties['object'], 3));
        $objName = str_replace("/", chr(92), $objName);
        $properties['className'] = $objName;

        $session = new Container($id);
        $session->properties = serialize($properties);

        $this->properties = $properties;
        return $this;
    }

    public function mergeProperties($id, $arrayData)
    {
        $properties = $this->getProperties();
        $nProperties = include(__DIR__ ."/../../../view/graphic-object-templating/" .$arrayData);
        $properties = array_merge($properties, $nProperties);
        $this->setProperties($properties);
        return $this;
    }

    public function setProperties(array $arrayProperties)
    {
        $properties = $this->getProperties();
        foreach ($arrayProperties as $key => $arrayProperty) {
            $properties[$key] = $arrayProperty;
        }

        $session = new Container($properties['id']);
        $session->properties = serialize($properties);

        $this->properties    = $properties;
        return $this;
    }

    public function getProperties() {
        if (isset($this->properties)) {
            $id = $this->properties['id'];
            $session = new Container($id);
            return unserialize($session->properties);
        }
        return false;
    }

    public function setId($id)
    {
        $id = (string) $id;
        $properties = $this->getProperties();
        $properties['id'] = $id;
        if (empty($properties['name'])) $properties['name'] = $id;
        $this->setProperties($properties);
        return $this;
    }

    public function getId()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('id', $properties)) ? $properties['id'] : false);
    }

    public function setName($name)
    {
        $name = (string) $name;
        $properties = $this->getProperties();
        $properties['name'] = $name;
        $this->setProperties($properties);
        return $this;
    }

    public function getName()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('name', $properties)) ? $properties['name'] : false);
    }

    public function setTemplate($template)
    {
        $properties = $this->getProperties();
        $template      = (string) $template;
        $templateName  = 'graphic-object-templating/oobject/'.$properties['typeObj'];
        $templateName .= '/'.$properties['object'].'/'.$template;

        $properties['template'] = $templateName;
        $this->setProperties($properties);
        return $this;
    }

    public function getTemplate()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('template', $properties)) ? $properties['template'] : false);
    }

    public function setDisplay($display = OObject::DISPLAY_BLOCK)
    {
        $displays = $this->getDisplayConstants();
        if (!in_array($display, $displays)) $display = OObject::DISPLAY_BLOCK;

        $properties            = $this->getProperties();
        $properties['display'] = $display;
        $this->setProperties($properties);
        return $this;
    }

    public function getDisplay()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('display', $properties)) ? $properties['display'] : false);
    }

    public function setStyle($style)
    {
        $style               = (string) $style;
        $properties          = $this->getProperties();
        $properties['style'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function addStyle($addStyle)
    {
        $addStyle            = (string) $addStyle;
        $properties          = $this->getProperties();
        $style               = "";
        if (isset($properties['style']))
			$style               = $properties['style'];
        if (substr($style, strlen($style) - 1, 1) !=";") $style .= ';';
        $style              .= $addStyle;
        $properties['style'] = $style;
        $this->setProperties($properties);
        return $this;
    }

    public function getStyle()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('style', $properties)) ? $properties['style'] : false);
    }

    public function setClasses($classes)
    {
        $classes               = (string) $classes;
        $properties            = $this->getProperties();
        $properties['classes'] = $classes;
        $this->setProperties($properties);
        return $this;
    }

    public function addClass($addClass)
    {
        $addClass              = (string) $addClass;
        $properties            = $this->getProperties();
        $classes               = $properties['classes'];
        if (substr($classes, strlen($classes) - 1, 1) !=" ") $classes .= ' ';
        $classes              .= trim($addClass);
        $properties['classes'] = $classes;
        $this->setProperties($properties);
        return $this;
    }

    public function getClasses()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('classes', $properties)) ? $properties['classes'] : false);
    }

    public function setInfoBulle($infoBulle)
    {
        $infoBulle               = (string) $infoBulle;
        $properties              = $this->getProperties();
        $properties['infoBulle'] = $infoBulle;
        $this->setProperties($properties);
        return $this;
    }

    public function getInfoBulle()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('infoBulle', $properties)) ? $properties['infoBulle'] : false);
    }

    public function setInfoBullePosition($iBPosition = self::TOOLTIP_TOP)
    {
        $constantes = $this->getTooltipsConst();
        if (!in_array($iBPosition, $constantes)) $iBPosition = self::TOOLTIP_TOP;

        $properties               = $this->getProperties();
        $properties['iBPosition'] = $iBPosition;
        $this->setProperties($properties);
        return $this;
    }

    public function getInfoBullePosition()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('iBPosition', $properties)) ? $properties['iBPosition'] : false);
    }

    public function setWidthBT($widthBT)
    {
        $properties = $this->getProperties();
        $properties['widthBT'] = $widthBT;
        $this->setProperties($properties);
        return $this;
    }

    public function getWidthBT()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('widthBT', $properties)) ? $properties['widthBT'] : false);
    }

    static public function buildObject($id, $value = null)
    {
        $session = new Container($id);
        $properties = unserialize($session->properties);

        if (!empty($properties)) {
            if ($properties['typeObj'] == "omcomposed") {
                $children = $properties['children'];
                $propertiesChild = [];
                foreach ($children as $idObj => $child) {
                    $objet = OObject::buildObject($idObj);
                    $propertiesChild[$idObj] = $objet->getProperties();
                }
                $obj = new $properties['className']($properties['id']);
                foreach ($propertiesChild as $idObj => $propertyChild) {
                    $name = $propertyChild['name'];
                    $obj->$name->setProperties($propertyChild);
                }
            } else {
                $obj = new $properties['className']($properties['id']);
            }
            $obj->setProperties($properties);
            if (!empty($value)) $obj->setValue($value);
            return $obj;
        } else {
            throw new \Exception("objet $id inexistant en session");
        }
    }

    public function getResources()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('resources', $properties)) ? $properties['resources'] : false);
    }

    public function addCssResource($cssResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) $properties['resources'] = [];
        if (!isset($properties['resources']['css'])) $properties['resources']['css'] = [];

        switch(true){
            case (is_string($cssResource)):
                $properties['resources']['css'][] = $cssResource;
                break;
            case (is_array($cssResource)):
                $properties['resources']['css'] = array_merge($properties['resources']['css'], $cssResource);
                break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function getCssResource()
    {
        $properties = $this->getProperties();
        if (array_key_exists('resources', $properties)) {
            $tmp = $properties['resources'];
            return ((array_key_exists('css', $tmp)) ? $tmp['css'] : false);
        }
        return false;
    }

    public function setCssResource($cssResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) $properties['resources'] = [];
        if (!isset($properties['resources']['css'])) $properties['resources']['css'] = [];
        $properties['resources']['css'] = [];

        switch(true){
            case (is_string($cssResource)):
                $properties['resources']['css'][] = $cssResource;
                break;
            case (is_array($cssResource)):
                $properties['resources']['css'] =  $cssResource;
                break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function addJsResource($jsResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) $properties['resources'] = [];
        if (!isset($properties['resources']['js'])) $properties['resources']['js'] = [];

        switch(true){
            case (is_string($jsResource)):
                $properties['resources']['js'][] = $jsResource;
                break;
            case (is_array($jsResource)):
                $properties['resources']['js'] = array_merge($properties['resources']['js'], $jsResource);
                break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function getJsResource()
    {
        $properties = $this->getProperties();
        if (array_key_exists('resources', $properties)) {
            $tmp = $properties['resources'];
            return ((array_key_exists('js', $tmp)) ? $tmp['js'] : false);
        }
        return false;
    }

    public function setJsResource($jsResource)
    {
        $properties = $this->getProperties();
        if (!isset($properties['resources'])) $properties['resources'] = [];
        if (!isset($properties['resources']['js'])) $properties['resources']['js'] = [];
        $properties['resources']['js'] = [];

        switch(true){
            case (is_string($jsResource)):
                $properties['resources']['js'][] = $jsResource;
                break;
            case (is_array($jsResource)):
                $properties['resources']['js'] = $jsResource;
                break;
        }
        $this->setProperties($properties);
        return $this;
    }

    public function setAclReference($aclReference)
    {
        $aclReference = (string) $aclReference;
        $properties = $this->getProperties();
        $properties['aclReference'] = $aclReference;
        $this->setProperties($properties);
        return $this;
    }

    public function getAclRefence()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('aclReference', $properties)) ? $properties['aclReference'] : false);
    }

    public function setObject($object)
    {
        $object = (string) $object;
        $properties = $this->getProperties();
        $properties['object'] = $object;
        $this->setProperties($properties);
        return $this;
    }

    public function getObject()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('object', $properties)) ? $properties['object'] : false);
    }

    public function setTypeObj($typeObj)
    {
        $typeObj = (string) $typeObj;
        $properties = $this->getProperties();
        $properties['typeObj'] = $typeObj;
        $this->setProperties($properties);
        return $this;
    }

    public function getTypeObj()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('typeObj', $properties)) ? $properties['typeObj'] : false);
    }

    public function setClassName($className)
    {
        $className = (string) $className;
        $properties = $this->getProperties();
        $properties['className'] = $className;
        $this->setProperties($properties);
        return $this;
    }

    public function getClassName()
    {
        $properties = $this->getProperties();
        return ((array_key_exists('className', $properties)) ? $properties['className'] : false);
    }
    
    /*
     * méthode interne à la classe OObject
     */

    protected function getConstants()
    {
        $ref = new \ReflectionClass($this);
        return $ref->getConstants();
    }

    private function getDisplayConstants()
    {
        $constants = $this->getConstants();
        foreach ($constants as $key => $constant) {
            $pos = strpos($constant, 'DISPLAY_');
            if ($pos === false) unset($constants[$key]);
        }
        return $constants;
    }

    private function getTooltipsConst()
    {
        $constants = $this->getConstants();
        foreach ($constants as $key => $constant) {
            $pos = strpos($key, 'TOOLTIP_');
            if ($pos === false) unset($constants[$key]);
        }
        return $constants;
    }


}
