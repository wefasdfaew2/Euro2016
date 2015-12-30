<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class BetController extends Controller
{

    public function listAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bets = $rep->findBy(array('user' => $this->getUser()));
        if(sizeof($bets) > 0)
        {
            $response = array(
                'status_code' => 200,
                'data' => $bets
            );
        }
        else
        {
            $response = array(
                'status_code' => 500,
                'data' => array(
                    'message'	=> 'There are no bets in the database',
                    'type'      => 'warning',
                    'buttons'   => array(
                        array(
                            'type'  => 'warning',
                            'link'  => $this->generateUrl('eu_main_homepage'),
                            'text'  => 'Dashboard'
                        )
                    )
                )
            );
        }

        if($request->isXmlHttpRequest())
        {
            return new JsonResponse($response);
        }
        else
        {
            if($response['status_code'] <> 200)
            {
                return $this->render('EUMainBundle:Index:message.html.twig', array('alert' => $response['data']));
            }
            else
            {
                return $this->render('EUMainBundle:Bet:list.html.twig', array('bets' => $response['data']));
            }
        }
    }

    public function createAction()
    {

    }

    public function readAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bet = $rep->find($id);
        if($bet)
        {
            $response = array(
                'status_code' => 200,
                'data' => $bet
            );
        }
        else
        {
            $response = array(
                'status_code' => 500,
                'data' => array(
                    'message'	=> 'There is no bet with this ID in the database',
                    'type'      => 'warning',
                    'buttons'   => array(
                        array(
                            'type'  => 'warning',
                            'link'  => $this->generateUrl('eu_main_homepage'),
                            'text'  => 'Dashboard'
                        )
                    )
                )
            );
        }

        if($request->isXmlHttpRequest())
        {
            return new JsonResponse($response);
        }
        else
        {
            if($response['status_code'] <> 200)
            {
                return $this->render('EUMainBundle:Index:message.html.twig', array('alert' => $response['data']));
            }
            else
            {
                //Render an HTML page
            }
        }
    }

    public function updateAction($id)
    {

    }

    public function deleteAction($id)
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bet = $rep->find($id);
        if($bet)
        {
            $em->remove($bet);
            $response = array(
                'status_code' => 200,
                'data' => array(
                    'message'	=> 'The '.$bet.' has been deleted',
                    'type'      => 'success',
                    'buttons'   => array(
                        array(
                            'type'  => 'success',
                            'link'  => $this->generateUrl('eu_main_homepage'),
                            'text'  => 'Dashboard'
                        )
                    )
                )
            );
            $em->flush();
        }
        else
        {
            $response = array(
                'status_code' => 500,
                'data' => array(
                    'message'	=> 'There is no bet with this ID in the database',
                    'type'      => 'warning',
                    'buttons'   => array(
                        array(
                            'type'  => 'warning',
                            'link'  => $this->generateUrl('eu_main_homepage'),
                            'text'  => 'Dashboard'
                        )
                    )
                )
            );
        }

        if($request->isXmlHttpRequest())
        {
            return new JsonResponse($response);
        }
        else
        {
            return $this->render('EUMainBundle:Index:message.html.twig', array('alert' => $response['data']));
        }
    }

}
