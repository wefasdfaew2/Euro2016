<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\ResponseHelper;
use EU\MainBundle\Entity\ResponseHelperControllerInterface;

class GameController extends Controller implements ResponseHelperControllerInterface
{

    public function getDefaultTemplate()
    {
        return 'EUMainBundle:Game:index.html.twig';
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Game');
        $games = $rep->findAll();
        $response = new ResponseHelper($this, Response::HTTP_OK, $games);
        return $response->renderResponse();
    }

    public function readAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Game');
        $game = $rep->find($id);
        $response = new ResponseHelper($this);
        if($game)
        {
            $response->setStatusCode(Response::HTTP_OK);
            $response->setData($game);
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no game with this ID in the database');
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
        $rep = $em->getRepository('EUMainBundle:Game');
        $game = $rep->find($id);
        $response = new ResponseHelper($this);
        if($game)
        {
            $rep = $em->getRepository('EUMainBundle:Bet');
            $bets = $rep->findBy(array('game' => $game));
            $response->setStatusCode(Response::HTTP_OK);
            $response->setData($bets);
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no game with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

}
