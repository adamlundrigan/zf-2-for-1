<?php

use Zend\ServiceManager\ServiceLocatorInterface;

class Zf2for1_Controller_Action_Helper_Zf2sm extends Zend_Controller_Action_Helper_Abstract
{
    protected $serviceLocator;

    /**
     * Direct calls on this action helper should proxy to the ZF2 Service Locator
     *
     * @return Zend\ServiceManager\ServiceLocatorInterface
     */
    public function __call($method, $arguments)
    {
        if (is_null($this->serviceLocator)) {
            $this->serviceLocator = $this->getActionController()->getInvokeArg('bootstrap')->getResource('zf2')->getServiceManager();
        }
        return call_user_func_array(array($this->serviceLocator, $method), $arguments);
    }

    public function setServiceLocator(ServiceLocatorInterface $sm)
    {
        $this->serviceLocator = $sm;
    }
}
