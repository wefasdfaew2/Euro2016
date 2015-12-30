<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PotController extends Controller
{

    public function listAction()
    {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Participation');
        $participations = $rep->findBy(array('user' => $this->getUser()));
        if(sizeof($participations) > 0)
        {
            $response = array(
                'status_code' => 200,
                'data' => $participations
            );
        }
        else
        {
            $response = array(
                'status_code' => 500,
                'data' => array(
                    'message'	=> 'There are no pots in the database',
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
                return $this->render('EUMainBundle:Pot:list.html.twig', array('participations' => $response['data']));
            }
        }
    }

}
