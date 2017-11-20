<?php

namespace GraphicObjectTemplating\Twig\Extension;

use Twig_Extension;
use Twig_Filter;

class GotTwigExtension extends Twig_Extension
{
    public function getFilters() {
        return array(
            new Twig_Filter('urlresource', [$this, 'twigFilter_urlresource']),
        );
    }

    public function twigFilter_urlresource($objet, $resourceName)
    {
        $resources = $objet['resources'];
        foreach ($resources as $resource) {
            if (array_key_exists($resourceName, $resource)) {
                return '/graphicobjecttemplating/objects/'.$objet['typeObj'].'/'.$objet['object'].'/'.$resource[$resourceName];
            }
        }
        return '';
    }
}