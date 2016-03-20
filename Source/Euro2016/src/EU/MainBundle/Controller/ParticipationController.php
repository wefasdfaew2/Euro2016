<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\ResponseHelper;
use EU\MainBundle\Entity\ResponseHelperControllerInterface;

class ParticipationController extends Controller implements ResponseHelperControllerInterface
{

    public function getDefaultTemplate()
    {
        return 'EUMainBundle:Pot:index.html.twig';
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Participation');
        $participations = $rep->findBy(array('user' => $this->getUser()));
        $response = new ResponseHelper($this, Response::HTTP_OK, $participations);
        return $response->renderResponse();
    }

    public function acceptAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Participation');
        $participation = $rep->find($id);
        $response = new ResponseHelper($this);
        if($participation)
        {
            if($participation->getUser() !== $this->getUser())
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
            else
            {
                if($participation->getAcceptedAt() == null)
                {
                    $participation->setAcceptedAt(new \DateTime());
                    $em->flush();
                }
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
            }
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no participation with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

    public function paidAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Participation');
        $participation = $rep->find($id);
        $response = new ResponseHelper($this);
        if($participation)
        {
            $pot = $participation->getPot();
            if($pot->getManager() !== $this->getUser())
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
            else
            {
                if($participation->getPaidAt() == null)
                {
                    $participation->setPaidAt(new \DateTime());
                    $em->flush();
                }
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
            }
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no participation with this ID in the database');
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
        $rep = $em->getRepository('EUMainBundle:Participation');
        $participation = $rep->find($id);
        $response = new ResponseHelper($this);
        if($participation)
        {
            if(($participation->getUser() !== $this->getUser()) && ($participation->getAcceptedAt() !== null))
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
            elseif(($participation->getUser() !== $this->getUser()) && ($participation->getPot()->getManager() !== $this->getUser()))
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
            else
            {
                $pot = $participation->getPot();
                $em->remove($participation);
                $em->flush();
                $participations = $rep->findBy(array('pot' => $pot));
                if(sizeof($participations) == 0)
                {
                    $em->remove($pot);
                }
                $em->flush();
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
            }
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no participation with this ID in the database');
            $response->setMessageType('warning');
            $response->addMessageButton('default', ($request->headers->get('referer') == '') ? $this->generateUrl('eu_main_homepage') : $request->headers->get('referer'), 'Back');
            $response->addMessageButton('warning', $this->generateUrl('eu_main_homepage'), 'Home');
        }
        return $response->renderResponse();
    }

}
