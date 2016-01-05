<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\ResponseHelper;

class TeamController extends Controller
{

    public function listAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Team');
        $teams = $rep->findAll();
        $response = new ResponseHelper($this, Response::HTTP_OK, $teams);
        return $response->renderResponse();
    }

    public function readAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Team');
        $team = $rep->find($id);
        $response = new ResponseHelper($this);
        if($team)
        {
            $response->setStatusCode(Response::HTTP_OK);
            $response->setData($team);
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no team with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

}
