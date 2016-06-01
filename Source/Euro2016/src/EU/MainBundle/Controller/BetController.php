<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\Bet;
use EU\MainBundle\Form\BetType;
use EU\MainBundle\Form\BetEditType;
use EU\MainBundle\Entity\ResponseHelper;
use EU\MainBundle\Entity\ResponseHelperControllerInterface;

class BetController extends Controller implements ResponseHelperControllerInterface
{

    public function getDefaultTemplate()
    {
        return 'EUMainBundle:Bet:index.html.twig';
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Participation');
        $user = $this->getUser();
        $participations = $rep->findBy(array('user' => $user));
        if(sizeof($participations) == 0)
        {
            return $this->render('EUMainBundle:Index:welcome.html.twig');
        }
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bets = $rep->findBy(array('user' => $user));
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
            $rep = $em->getRepository('EUMainBundle:Participation');
            $participation = $rep->findOneBy(array('user' => $this->getUser(), 'pot' => $bet->getPot()));
            $rep = $em->getRepository('EUMainBundle:Bet');
            $sameBet = $rep->findOneBy(array('user' => $this->getUser(), 'game' => $bet->getGame(), 'pot' => $bet->getPot()));
            if(!$bet->getGame())
            {
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
            }
            elseif(!$participation)
            {
                $response->setStatusCode(Response::HTTP_NOT_FOUND);
            }
            elseif($bet->getGame()->hasStarted())
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
            elseif(!$participation->isPaid())
            {
                $response->setStatusCode(Response::HTTP_PAYMENT_REQUIRED);
            }
            elseif($sameBet !== null)
            {
                $sameBet->updateBet($bet);
                $em->flush();
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
            }
            else
            {
                $em->persist($bet);
                $em->flush();
                $response->setStatusCode(Response::HTTP_CREATED);
                $response->addHeader('Location', $this->generateUrl('bets_read', array('id' => $bet->getId()), true));
            }
        }
        else
        {
            $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
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
            $form = $this->createForm(new BetEditType(), $bet);
            $form->submit($request);
            if($form->isValid())
            {
                $rep = $em->getRepository('EUMainBundle:Participation');
                $participation = $rep->findOneBy(array('user' => $this->getUser(), 'pot' => $bet->getPot()));
                if($bet->getGame()->hasStarted())
                {
                    $response->setStatusCode(Response::HTTP_LOCKED);
                }
                elseif(!$participation->isPaid())
                {
                    $response->setStatusCode(Response::HTTP_PAYMENT_REQUIRED);
                }
                else
                {
                    $em->flush();
                    $response->setStatusCode(Response::HTTP_NO_CONTENT);
                }
            }
            else
            {
                $response->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);
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
            if($bet->getGame()->hasStarted())
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
            else
            {
                $em->remove($bet);
                $em->flush();
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
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

}
