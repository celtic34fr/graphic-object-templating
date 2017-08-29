<?php 
namespace GraphicObjectTemplating\Twig\Extension;


use Twig_Filter;

class ColorConverterTwigExtension extends \Twig_Extension
{

    public function getName()
    {
        return 'Color Converter';
    }

    public function getFilters()
    {
        return array(
            new Twig_Filter('hexToRgb', [$this, 'twigFilter_convert_hexToRgb']),
        );
    }

    public function twigFilter_convert_hexToRgb($hex, $returnAsArray = false)
    {

	    $hex = str_replace("#", "", $hex);

	    if(strlen($hex) == 3) {
		    $r = hexdec(substr($hex,0,1).substr($hex,0,1));
		    $g = hexdec(substr($hex,1,1).substr($hex,1,1));
		    $b = hexdec(substr($hex,2,1).substr($hex,2,1));
	    } else {
		    $r = hexdec(substr($hex,0,2));
		    $g = hexdec(substr($hex,2,2));
		    $b = hexdec(substr($hex,4,2));
	    }

	    $rgb = array('r' => $r, 'g' => $g, 'b' => $b);

	    return $returnAsArray ? $rgb : implode(",", $rgb);

    }
}
