<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\Bet;
use EU\MainBundle\Form\BetType;
use EU\MainBundle\Entity\ResponseHelper;

class BetController extends Controller
{

    public function listAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bets = $rep->findBy(array('user' => $this->getUser()));
        $response = new ResponseHelper($this, Response::HTTP_OK, $bets);
        return $response->renderResponse();
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $requestData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $response = new ResponseHelper($this);
        $bet = new Bet();
        $bet->setUser($this->getUser());
        $form = $this->createForm(new BetType(), $bet);
        $form->submit($request);
        if($form->isValid())
        {
            $em->persist($bet);
            $em->flush();
            $response->setStatusCode(Response::HTTP_CREATED);
            $response->addHeader('Location', $this->generateUrl('eu_bet_read', array('id' => $bet->getId()), true));
        }
        else
        {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR );
        }
        return $response->renderResponse();
    }

    public function readAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bet = $rep->find($id);
        $response = new ResponseHelper($this);
        if($bet)
        {
            $response->setStatusCode(Response::HTTP_OK);
            $response->setData($bet);
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no bet with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

    public function updateAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bet = $rep->find($id);
        $response = new ResponseHelper($this);
        if($bet)
        {
            $form = $this->createForm(new BetType(), $bet);
            $form->submit($request);
            if($form->isValid())
            {
                $em->flush();
                //TO-DO: PATCH request (Partial update)
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
            }
            else
            {
                $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR );
            }
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no bet with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

    public function deleteAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bet = $rep->find($id);
        $response = new ResponseHelper($this);
        if($bet)
        {
            $em->remove($bet);
            $em->flush();
            $response->setStatusCode(Response::HTTP_NO_CONTENT);
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no bet with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

}
