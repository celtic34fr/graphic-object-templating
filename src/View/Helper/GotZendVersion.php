<?php

namespace GraphicObjectTemplating\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GotZendVersion extends AbstractHelper
{
    public function __invoke()
    {
        return \Zend\Version\Version::VERSION;
    }
}