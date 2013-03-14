<?php

use Zend\Mvc\Application;
use Zend\Stdlib\ResponseInterface;
use Zend\Mvc\Router\Http\RouteMatch;
use Zend\Mvc\ResponseSender\SendResponseEvent;

require_once __DIR__ . "/../ResponseSender.php";

class Zf2for1_Controller_Dispatcher_Abstract extends Zend_Controller_Dispatcher_Standard
{
    protected $zf2app;

    protected $dispatchToZf2 = false;

    protected $responseSender;

    public function dispatch(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response)
    {
        return ($this->checkZf2RouterForMatches())
            ? $this->dispatchRequestToZf2($request, $response)
            : parent::dispatch($request, $response);
    }

    protected function checkZf2RouterForMatches()
    {
        $sm = $this->zf2app->getServiceManager();
        $router = $sm->get('Router');
        $routeMatch = $router->match($this->zf2app->getMvcEvent()->getRequest());
        return ( $this->dispatchToZf2 = ( $routeMatch instanceof RouteMatch ) );
    }

    protected function dispatchRequestToZf2(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response)
    {
        $sender = $this->getResponseSender();
        $sender->setZf1Response($response);

        $this->getFrontController()->setParam('noErrorHandler', true);

        // Intercept the response and channel it back through Zend\Controller\Response\*
        $events = $this->zf2app->getServiceManager()->get('SendResponseListener')->getEventManager();
        $events->attach(SendResponseEvent::EVENT_SEND_RESPONSE, $sender, 999999);

        $this->zf2app->run();
    }

    public function hasDispatchedToZf2()
    {
        return $this->dispatchToZf2;
    }

    public function getResponseSender()
    {
        if (is_null($this->responseSender)) {
            $this->responseSender = new Zf2for1_Controller_ResponseSender();
        }
        return $this->responseSender;
    }

    public function setZf2Application(Application $o)
    {
        $this->zf2app = $o;
        return $this;
    }
}
