<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\ResponseHelper;

class PotController extends Controller
{

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $pots = $rep->findAll();
        $response = new ResponseHelper($this, Response::HTTP_OK, $pots);
        return $response->renderResponse();
    }

    public function readAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $pot = $rep->find($id);
        $response = new ResponseHelper($this);
        if($pot)
        {
            $response->setStatusCode(Response::HTTP_OK);
            $response->setData($pot);
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no pot with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

    public function listUsersAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $pot = $rep->find($id);
        $response = new ResponseHelper($this);
        if($pot)
        {
            $rep = $em->getRepository('EUMainBundle:Participation');
            $participations = $rep->findBy(array('pot' => $pot));
            $users = array();
            foreach ($participations as $p)
            {
                array_push($users, $p->getUser());
            }
            if(in_array($this->getUser(), $users))
            {
                $response->setStatusCode(Response::HTTP_OK);
                $response->setData($users);
            }
            else
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no pot with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

    public function listBetsAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $pot = $rep->find($id);
        $response = new ResponseHelper($this);
        if($pot)
        {
            $rep = $em->getRepository('EUMainBundle:Bet');
            $bets = $rep->findBy(array('pot' => $pot, 'user' => $this->getUser()));
            $rep = $em->getRepository('EUMainBundle:Participation');
            $participations = $rep->findBy(array('pot' => $pot));
            $users = array();
            foreach ($participations as $p)
            {
                array_push($users, $p->getUser());
            }
            if(in_array($this->getUser(), $users))
            {
                $response->setStatusCode(Response::HTTP_OK);
                $response->setData($bets);
            }
            else
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no pot with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

}
