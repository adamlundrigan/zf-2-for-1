<?php

class Zf2for1_Controller_Dispatcher_Zf1First extends Zf2for1_Controller_Dispatcher_Abstract
{
    public function isDispatchable(Zend_Controller_Request_Abstract $request)
    {
        return ( parent::isDispatchable($request) )
            ? true
            : $this->checkZf2RouterForMatches();
    }
}
