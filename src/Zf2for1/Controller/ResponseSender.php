<?php

use Zend\Mvc\ResponseSender\PhpEnvironmentResponseSender;
use Zend\Mvc\ResponseSender\SendResponseEvent;
use Zend\Http\Header\MultipleHeaderInterface;
use Zend\Http\PhpEnvironment\Response;

class Zf2for1_Controller_ResponseSender extends PhpEnvironmentResponseSender
{
    protected $zf1response;

    /**
     * Send HTTP headers
     *
     * @param SendResponseEvent $event
     * @return PhpEnvironmentResponseSender
     */
    public function sendHeaders(SendResponseEvent $event)
    {
        if (headers_sent() || $event->headersSent()) {
            return $this;
        }

        $response = $event->getResponse();
        foreach ($response->getHeaders() as $header) {
            if ($header instanceof MultipleHeaderInterface) {
                $this->zf1response->setHeader($header->getFieldName(), $header->getFieldValue(), false);
                continue;
            }
            $this->zf1response->setHeader($header->getFieldName(), $header->getFieldValue(), false);
        }
        $this->zf1response->setHttpResponseCode($response->getStatusCode());

        $event->setHeadersSent();
        return $this;
    }

    /**
     * Send content
     *
     * @param SendResponseEvent $event
     * @return PhpEnvironmentResponseSender
     */
    public function sendContent(SendResponseEvent $event)
    {
        if ($event->contentSent()) {
            return $this;
        }

        $response = $event->getResponse();
        $this->zf1response->setBody($response->getBody());

        $event->setContentSent();
        return $this;
    }


    public function setZf1Response(Zend_Controller_Response_Abstract $response)
    {
        $this->zf1response = $response;
        return $this;
    }
}
