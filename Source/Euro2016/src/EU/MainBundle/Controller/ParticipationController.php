<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\ResponseHelper;

class ParticipationController extends Controller
{

    public function listAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Participation');
        $participations = $rep->findBy(array('user' => $this->getUser()));
        $response = new ResponseHelper($this, Response::HTTP_OK, $participations);
        return $response->renderResponse();
    }

}
