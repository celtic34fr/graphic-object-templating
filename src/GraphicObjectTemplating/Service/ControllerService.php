<?php
/**
 * Created by PhpStorm.
 * User: gilbert
 * Date: 11/09/16
 * Time: 16:13
 */

namespace GraphicObjectTemplating\Service;


class ControllerService implements ControllerServiceInterface
{
    protected $service;

    public function __construct($service) {
        $this->service = $service;
    }

    public function getService() {
        return $this->service;
    }
}