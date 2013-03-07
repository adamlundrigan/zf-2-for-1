<?php
use Zend\ServiceManager\ServiceLocatorInterface;

class Zf2for1_Controller_Action_Helper_Zf2 extends Zend_Controller_Action_Helper_Abstract
{
    protected $serviceLocator;

    /**
     * Direct calls to this action helper should proxy to the ZF2 ControllerPluginManager
     *
     * @return Zend\Mvc\Controller\PluginManager
     */
    public function direct()
    {
        return $this->getServiceLocator()->get('ControllerPluginManager');
    } 

    /**
     * Method overloading: return/call plugins
     *
     * If the plugin is a functor, call it, passing the parameters provided.
     * Otherwise, return the plugin instance.
     *
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public function __call($method, $params)
    {
        $plugin = $this->direct()->get($method);
        if (is_callable($plugin)) {
            return call_user_func_array($plugin, $params);
        }
        return $plugin;
    }

    public function setServiceLocator(ServiceLocatorInterface $sm)
    {
        $this->serviceLocator = $sm;
    }

    public function getServiceLocator()
    {
        if (is_null($this->serviceLocator)) {
            $this->serviceLocator = $this->getActionController()->getInvokeArg('bootstrap')->getResource('zf2')->getServiceManager();
        }
        return $this->serviceLocator;
    }
}
