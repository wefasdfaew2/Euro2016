<?php

namespace EU\MainBundle\Entity;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use EU\MainBundle\Entity\ResponseHelperControllerInterface;

class ResponseHelper
{

    private $controller;
    private $statusCode;
    private $headers;
    private $data;
    private $message;
    private $messageType;
    private $messageButtons;

    public function __construct(ResponseHelperControllerInterface $controller, $statusCode = 200, $data = null, $headers = array())
    {
        $this->controller = $controller;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->messageButtons = array();
        $this->data = $data;
    }

    public function renderResponse()
    {
        if($this->controller->getRequest()->isXmlHttpRequest())
        {
            return new JsonResponse($this->getData(), $this->getStatusCode(), $this->getHeaders());
        }
        else
        {
            if(!$this->isSuccess())
            {
                return $this->controller->render('EUMainBundle:Index:message.html.twig', array(
                    'alert' => array(
                        'message' => $this->getMessage(),
                        'type' => $this->getMessageType(),
                        'buttons' => $this->getMessageButtons()
                    )
                ));
            }
            else
            {
                return $this->controller->render($this->controller->getDefaultTemplate(), array(
                    'data' => $this->getData()
                ));
            }
        }
    }

    public function isSuccess()
    {
        return $this->getStatusCode() < 299;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function addHeader($key, $header)
    {
        $this->headers[$key] = $header;
        return sizeof($this->headers);
    }

    public function removeHeader($headerKey)
    {
        if(array_key_exists($headerKey, $this->headers))
        {
            unset($this->headers[$headerKey]);
            return true;
        }
        else
        {
            return false;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    public function getMessageType()
    {
        return $this->messageType;
    }

    public function setMessageType($messageType)
    {
        $this->messageType = $messageType;
        return $this;
    }

    public function getMessageButtons()
    {
        return $this->messageButtons;
    }

    public function addMessageButton($type, $link, $text)
    {
        array_push($this->messageButtons, array(
            'type' => $type,
            'link' => $link,
            'text' => $text
        ));
        return sizeof($this->messageButtons);
    }
}
