<?php

namespace GraphicObjectTemplating\Twig\Extension;

use Twig_Extension;

class GotTwigExtension extends Twig_Extension
{
    public function getFilters() {
        return array(
            'urlresource'       => new \Twig_Filter_Method($this, 'getUrlResource'),
        );
    }

    public function getUrlResource($objet, $resourceName)
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