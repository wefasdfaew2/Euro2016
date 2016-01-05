<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\ResponseHelper;

class PotController extends Controller
{

    public function listAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $pots = $rep->findAll();
        $response = new ResponseHelper($this, Response::HTTP_OK, $pots);
        return $response->renderResponse();
    }

}
