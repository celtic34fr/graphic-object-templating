<?php
/**
 * Created by PhpStorm.
 * User: main
 * Date: 07/02/16
 * Time: 15:01
 */

namespace GraphicObjectTemplating\Twig\Extension;

use Exception;
use GraphicObjectTemplating\Twig\Extension\TokenParser\TokenParserSwitch;
use Traversable;
use Twig_Error_Runtime;

class LayoutExtension extends \Twig_Extension
{

    public function getFunctions() {
        return array(
            'getclass'       => new \Twig_Function_Method($this, 'getClass'),
            'gettype'        => new \Twig_Function_Method($this, 'getType'),
            'arrayexception' => new \Twig_Function_Method($this, 'arrayException'),
            'checkBoolean'   => new \Twig_Function_Method($this, 'isBoolean'), // KALANTwigExtension @author LAURE
            'substr'         => new \Twig_Function_Method($this, 'subString'),
            'strpos'         => new \Twig_Function_Method($this, 'strPos'),
            'instring'       => new \Twig_Function_Method($this, 'inString'),
        );
    }

    public function getTests()
    {
        return array(
            'typeOf'        => new \Twig_Function_Method($this, 'typeOf'),
            'objInstanceOf' => new \Twig_Function_Method($this, 'ObjectInstanceOf'),
        );
    }

    public function getTokenParsers()
    {
        return array(
            new TokenParserSwitch(), // unaccepted token (fabpot) update maxgalbu to work with Twig >= 1.12
        );
    }

    public function getFilters()
    {
        return array(
            'update' => new \Twig_Filter_Method($this, 'twig_array_update'),
        );
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'layout_extension';
    }


    public function ObjectInstanceOf($object, $class)
    {
        $reflectionClass = new \ReflectionClass($class);
        return $reflectionClass->isInstance($object);
    }

    public function inString($var1, $var2)
    { // is var1 in var2
        $var1 = (string) $var1;
        $var2 = (string) $var2;
        return (strpos($var2, $var1) !== false) ? true : false;
    }

    public function getType($object = NULL)
    {
        return gettype($object);
    }

    public function getClass($object = NULL)
    {
        if (gettype($object) == "object") {
            return get_class($object);
        } else {
            return "noClass";
        }
    }

    public function arrayException(Exception $exception)
    {
        $retArray = [];
        $retArray['File']          = $exception->getFile();
        $retArray['Line']          = $exception->getLine();
        $retArray['Message']       = $exception->getMessage();
        $retArray['TraceAsString'] = $exception->getTraceAsString();

        $tmpException              = $exception->getPrevious();
        $arrayPrevious             = [];
        while ($tmpException) {
            $item                  = [];
            $item['Class']         = get_class($tmpException);
            $item['File']          = $tmpException->getFile();
            $item['Line']          = $tmpException->getLine();
            $item['Message']       = $tmpException->getMessage();
            $item['TraceAsString'] = $tmpException->getTraceAsString();

            $arrayPrevious[]       = $item;
            $tmpException          = $tmpException->getPrevious();
        }

        $retArray['Previous']      = $arrayPrevious;

        return $retArray;
    }
    
	/**
	 * Description of KALANTwigExtension
	 * => permet le test sur le type d'un champs dans un template
	 *
	 * @author LAURE
	 */
	public function isBoolean($var) { return is_bool($var); }
	 
    public function typeOf($var, $type_test=null)
    {

        switch ($type_test) {
            default:
                return false;
                break;
            case 'array':
                return is_array($var);
                break;
            case 'bool':
                return is_bool($var);
                break;
            case 'float':
                return is_float($var);
                break;
            case 'int':
                return is_int($var);
                break;
            case 'numeric':
                return is_numeric($var);
                break;
            case 'object':
                return is_object($var);
                break;
            case 'scalar':
                return is_scalar($var);
                break;
            case 'string':
                return is_string($var);
                break;
            case 'datetime':
                return ($var instanceof \DateTime);
                break;
        }
    }

    public function subString($str, $start, $len= null)
    {
        if (intval($len) > 0 ) {
            return substr($str, $start, $len);
        } else {
            return substr($str, $start);
        }
    }

    public function strPos($str, $search, $offset = null)
    {
        if (intval($offset) > 0) {
            return strpos($str, $search, $offset);
        } else {
            return strpos($str, $search);
        }
    }

    /**
     * Merges an array with another one.
     *
     * <pre>
     *  {% set items = { 'apple': 'fruit', 'orange': 'fruit' } %}
     *
     *  {% set items = items|update({ 'apple': 'granny' }) %}
     *
     *  {# items now contains { 'apple': 'granny', 'orange': 'fruit' } #}
     * </pre>
     *
     * @param array|Traversable $arr1 An array
     * @param array|Traversable $arr2 An array (of keys / value)
     *
     * @return array The replace values in array by array of keys / values
     */
    function twig_array_update($arr1, $arr2)
    {
        if ($arr1 instanceof Traversable) {
            $arr1 = iterator_to_array($arr1);
        } elseif (!is_array($arr1)) {
            throw new Twig_Error_Runtime(sprintf('The merge filter only works with arrays or "Traversable", got "%s" as first argument.', gettype($arr1)));
        }

        if ($arr2 instanceof Traversable) {
            $arr2 = iterator_to_array($arr2);
        } elseif (!is_array($arr2)) {
            throw new Twig_Error_Runtime(sprintf('The merge filter only works with arrays or "Traversable", got "%s" as second argument.', gettype($arr2)));
        }

        foreach ($arr2 as $key => $value) {
            if (array_key_exists($key, $arr1)) $arr1[$key] = $value;
        }
        return $arr1;
    }
}
