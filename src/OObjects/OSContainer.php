<?php


namespace GraphicObjectTemplating\OObjects;


use Exception;
use InvalidArgumentException;
use LogicException;
use RuntimeException;
use UnexpectedValueException;

/**
 * Class OSContainer
 * @package GraphicObjectTemplating\OObjects
 *
 * méthodes
 * --------
 * __construct(string $id, array $properties)
 * __isset(string $key) : bool
 * __get(string $key)
 * __set(string $key, $val)
 *
 * addChild(OObject $child, string $mode = self::MODE_LAST, $params = null)
 * isChild($child) : bool
 * r_isChild(string $searchChild, OObject $child, string $path = '') : string
 * rmChild($child)
 * r_isset(string $key, OObject $child) : bool
 */

class OSContainer extends OObject
{
    const MODE_LAST     = 'last';
    const MODE_FIRST    = 'first';
    const MODE_BEFORE   = 'before';
    const MODE_AFTER    = 'after';
    const MODE_NTH      = 'nth';

    /**
     * OSContainer constructor.
     * @param string $id
     * @param array $properties
     */
    protected function __construct(string $id, array $properties)
    {
        parent::__construct($id, $properties);
        $this->properties = $this->constructor($id, $properties);
    }

    /**
     * @param string $id
     * @param array $properties
     * @return array
     */
    public function constructor($id, $properties) : array
    {
        $properties = parent::constructor($id, $properties);

        $path = __DIR__. '/../../params/oobjects/oscontainer/oscontainer.config.php';
        $odc_properties = require $path;
        return $this->merge_properties($odc_properties, $properties);
    }


    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key) : bool
    {
        return $this->r_isset($key, $this) || array_key_exists($key, $this->properties);
    }

    /**
     * @param string $key
     * @return mixed|null
     * @throws Exception
     */
    public function __get(string $key)
    {
        if (!array_key_exists($key, $this->properties)) {
            $children = $this->properties['children'];
            if (array_key_exists($key, $children)) {
                return $children[$key];
            }
        }
        return parent::__get($key);
    }

    /**
     * @param string $key
     * @param $val
     * @return bool|mixed|null
     * @throws \ReflectionException
     */
    public function __set(string $key, $val)
    {
        switch ($key) {
            case 'form':
                $val = (string)$val;
				break;
            case 'codeCSS':
                break;
            case 'children':
                if (is_array($val)) {
                    $ok = true;
                    foreach ($val as $cle=>$valeur){
                        if (!($valeur instanceof OObject)) {
                            $ok = false;
                            break;
                        }
                    }
                    if (!$ok) {
                        throw new UnexpectedValueException("Au moins un élément tableau d'enfant non objet");
                    }
                } else {
                    throw new InvalidArgumentException("Valeur paramètre 'children' no tableau");
                }
                break;
            default:
                $children = $this->properties['children'];
                if (!array_key_exists($key, $children) && $val instanceof OObject) {
                    $this->properties['children'][$key] = $val;
                    return true;
                }
                return parent::__set($key, $val);
        }
        $this->properties[$key] = $val;
        return true;
    }

    /**
     * @param OObject $child    objet de type OObject (ou objet étendu) à insérer
     * @param string $mode      mode d'insertion :
     *                  MODE_LAST   : insertion en fin de tableau des enfants
     *                  MODE_AFTER  : insertion après l'enfant précidé par $params
     *                  MODE_BEFORE : insertion avant l'enfant précisé par $params
     *                  MODE_FIRST  : insertion en début de tableau des enfants
     *                  MODE_NTH    : insertion directe au rang donné par $params (numérique obligatoire)
     * @param null $params      paramètre pour l'insertion :
     *                  si numérique, correspond au d'insertion de child
     *                  si alphanumérique, clé de l'enfant avant ou après lequel on doit insérer child
     */
    public function addChild(OObject $child, string $mode = self::MODE_LAST, $params = null)
    {
        if (!$this->existChild($child)) {
            $children = (array) $this->children;
            switch($mode) {
                case self::MODE_LAST:
                    $children[$child->id] = $child;
                    break;
                case self::MODE_FIRST:
                    $newChild = [];
                    $newChild[$child->id] = $child;
                    $children = array_merge($newChild, $children);
                    break;
                case self::MODE_AFTER:
                case self::MODE_BEFORE:
                    if (!$params || is_numeric($params)) {
                        throw new InvalidArgumentException("Paramètre insertion" . $params . " numeric au lieu string");
                    }
                    if (!array_key_exists($params, $children)) {
                        throw new UnexpectedValueException("Paramètre nom enfant " . $params . " inconnu");
                    }
                    $new_children = [];
                    foreach ($children as $name=>$oChild) {
                        if (self::MODE_BEFORE && $name === $params) {
                            $new_children[$name] = $oChild;
                        }
                        $newChildren[$child->id] = $child;
                        if (self::MODE_AFTER && $name === $params){
                            $new_children[$name] = $oChild;
                        }
                    }
                    $children = $new_children;
                    break;
                case self::MODE_NTH:
                    if (!$params || !is_int($params)) {
                        throw new InvalidArgumentException("Rang " . $params . " non numérique");
                    }
                    if ((int)$params > count($this->children)) {
                        throw new InvalidArgumentException("Numéro d'ordre " . $params . " supérieur au nombre d'enfant");
                    }
                    $new_children = [];
                    $compteur = 0;
                    foreach ($children as $name=>$oChild) {
                        $compteur++;
                        if ($compteur === (int)$params) {
                            $new_children[$child->id] = $child;
                        }
                        $new_children[$name] = $oChild;
                    }
                    $children = $new_children;
                    break;
                default:
                    throw new UnexpectedValueException('Unexpected value');
            }
            $this->children = $children;
            return true;
        }
        throw new LogicException("Objet ".$child->id." déjà présent");
    }

    /**
     * @param $child
     * @return $this|false
     * @throws Exception
     */
	public function rmChild($child) 
	{
        if ($child instanceof OObject) {
            $child = $child->id;
        }
        if (!is_string($child)) {
            throw new InvalidArgumentException("demande de suppression impossible, passer soit un objet OObject soit un identifiant");
        }

        /** @var array $children */
        $children = $this->children;
        if (array_key_exists($child, $children)) {
            $children = array_diff_key($children, [$child]);
            $this->children = $children;
            return $this;
        }
		return false;
	}

    /**
     * @param $child
     * @return false|string
     * @throws Exception
     */
    public function isChild($child)
    {
        if ($child instanceof OObject) {
            $child = $child->id;
        }
        if (!is_string($child)) {
            throw new InvalidArgumentException("Recherche impossible, passer soit un objet OObject soit un identifiant");
        }

        $path = $this->r_isChild($child, $this);
        return $path ? $path : false;
    }

    /**
     * @param string $searchChild
     * @param OObject $child
     * @param string $path
     * @return string
     */
    public function r_isChild(string $searchChild, OObject $child, string $path = '') : string
    {
        $children = $child->children;
        if (array_key_exists($searchChild, $children)) {
            $path .= '.'.$child->id;
        } else {
            foreach ($children as $childId => $childBody) {
                $r_path = '.'.$this->r_isChild($child, $childBody);
                if ($r_path) {
                    $path .= '.' . $child->id . '.' . $r_path;
                }
            }
        }
        return $path;
    }

    /**
     * @param string $key
     * @param OObject $child
     * @return bool
     */
    public function r_isset(string $key, OObject $child) : bool
    {
        $r_isset = false;
        if ($child->children) {
            foreach ($child->children as $childId => $childBody) {
                $r_isset = $r_isset || $this->r_isset($key, $childBody) || ($childId == $key);
                if ($r_isset) { break; }
            }
        }
        return $r_isset;
    }

    /**
     * @param OObject|string $child
     * @return bool
     */
    public function existChild($child)
    {
        if ($child instanceof OObject) {
            $child = $child->id;
        }
        return $this->r_isset($child, $this);
    }

    /**
     * @return bool
     */
    public function hasChild(): bool
    {
        return count($this->children) > 0;
    }
}