<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\ResponseHelper;
use EU\MainBundle\Entity\ResponseHelperControllerInterface;
use EU\MainBundle\Entity\Bet;
use EU\MainBundle\Entity\Pot;
use EU\MainBundle\Entity\Participation;
use EU\MainBundle\Form\PotType;

class PotController extends Controller implements ResponseHelperControllerInterface
{

    public function getDefaultTemplate()
    {
        return 'EUMainBundle:Pot:index.html.twig';
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $pots = $rep->findAll();
        $response = new ResponseHelper($this, Response::HTTP_OK, $pots);
        return $response->renderResponse();
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $requestData = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $response = new ResponseHelper($this);
        $pot = new Pot();
        $pot->setManager($this->getUser());
        $form = $this->createForm(new PotType(), $pot);
        $form->submit($request);
        if($form->isValid())
        {
            $em->persist($pot);
            $em->flush();
            $participation = new Participation();
            $participation->setUser($this->getUser());
            $participation->setPot($pot);
            //Automatic acceptance and payment by manager
            $participation->setAcceptedAt(new \DateTime());
            //$participation->setPaidAt(new \DateTime());
            $em->persist($participation);
            $em->flush();
            $response->setStatusCode(Response::HTTP_CREATED);
            $response->addHeader('Location', $this->generateUrl('pots_read', array('id' => $pot->getId()), true));
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

    public function updateAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $pot = $rep->find($id);
        $response = new ResponseHelper($this);
        if($pot)
        {
            $form = $this->createForm(new PotType(), $pot);
            $form->submit($request);
            if($form->isValid())
            {
                //Not allowed at this stage
                if(true)
                {
                    $response->setStatusCode(Response::HTTP_LOCKED);
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
            $response->setMessage('There is no pot with this ID in the database');
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
        $rep = $em->getRepository('EUMainBundle:Pot');
        $pot = $rep->find($id);
        $response = new ResponseHelper($this);
        if($pot)
        {
            $rep = $em->getRepository('EUMainBundle:Participation');
            $participations = $rep->findBy(array('pot' => $pot));
            if($pot->getManager() !== $this->getUser())
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
            elseif (sizeof($participations) > 1)
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
            else
            {
                $participation = $rep->findOneBy(array('pot' => $pot, 'user' => $this->getUser()));
                $em->remove($participation);
                $em->flush();
                $em->remove($pot);
                $em->flush();
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
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

    public function inviteAction($id, $iduser)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $pot = $rep->find($id);
        $rep = $em->getRepository('ApplicationSonataUserBundle:User');
        $user = $rep->find($iduser);
        $response = new ResponseHelper($this);
        if($pot && $user)
        {
            $rep = $em->getRepository('EUMainBundle:Participation');
            $participations = $rep->findBy(array('pot' => $pot, 'user' => $user));
            if(sizeof($participations) > 0)
            {
                $response->setStatusCode(Response::HTTP_NO_CONTENT);
            }
            elseif ($pot->getManager() !== $this->getUser())
            {
                $response->setStatusCode(Response::HTTP_LOCKED);
            }
            else
            {
                $participation = new Participation();
                $participation->setUser($user);
                $participation->setPot($pot);
                $em->persist($participation);
                $em->flush();
                $response->setStatusCode(Response::HTTP_CREATED);
            }
        }
        else
        {
            $response->setStatusCode(Response::HTTP_NOT_FOUND);
            $response->setMessage('There is no pot or no user with this ID in the database');
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

    public function listParticipationsAction($id)
    {
        return $this->listUsersAction($id);
    }

}
