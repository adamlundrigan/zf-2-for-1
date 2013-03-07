<?php

class Zf2for1_Controller_Action_Helper_Zf2 extends Zend_Controller_Action_Helper_Abstract
{
    protected $serviceManager;

    /**
     * Direct calls to this action helper should proxy to the ZF2 ControllerPluginManager
     *
     * @return Zend\Mvc\Controller\PluginManager
     */
    public function direct()
    {
        return $this->getServiceManager()->get('ControllerPluginManager');
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

    public function getServiceManager()
    {
        if (is_null($this->serviceManager)) {
            $this->serviceManager = $this->getActionController()->getInvokeArg('bootstrap')->getResource('zf2')->getServiceManager();
        }
        return $this->serviceManager;
    }
}
