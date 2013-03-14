<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ResponseInterface;
use Zend\Mvc\Router\Http\RouteMatch;

class Zf2for1_Controller_Dispatcher_Zf2First extends Zf2for1_Controller_Dispatcher_Abstract
{
    public function isDispatchable(Zend_Controller_Request_Abstract $request)
    {
        return $this->checkZf2RouterForMatches()
            ? true
            : parent::isDispatchable($request); 
    }
}
