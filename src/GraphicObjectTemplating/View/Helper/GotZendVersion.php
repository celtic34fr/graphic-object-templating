<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 24/05/17
 * Time: 23:40
 */

namespace GraphicObjectTemplating\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GotZendVersion extends AbstractHelper
{
    public function __invoke()
    {
        return \Zend\Version\Version::VERSION;
    }
}