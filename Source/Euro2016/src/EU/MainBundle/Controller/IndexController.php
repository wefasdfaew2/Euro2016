<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EU\MainBundle\Entity\Bet;
use EU\MainBundle\Entity\Game;
use EU\MainBundle\Entity\Team;
use EU\MainBundle\Entity\Pot;
use EU\MainBundle\Entity\Participation;

class IndexController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $mainPot = $rep->find(1);
        $rep = $em->getRepository('EUMainBundle:Participation');
        $user = $this->getUser();
        $participations = $rep->findBy(array('user' => $this->getUser()));
        if(sizeof($participations) == 0)
        {
            return $this->render('EUMainBundle:Index:welcome.html.twig');
        }
        $unpaidParticipations = array();
        foreach ($participations as $p)
        {
            if(!$p->isPaid())
            {
                array_push($unpaidParticipations, $p);
            }
        }
        if(sizeof($unpaidParticipations) > 0)
        {
            $list = '';
            foreach($unpaidParticipations as $p)
            {
                $list = $list.'<li>'.$p->getPot()->getName().': '.$p->getPot()->getAmount().'&euro;</li>';
            }
            $this->get('session')->getFlashBag()->add(
                'info',
                '<h3>Participations unpaid</h3>Some of your participations in pots are unpaid:<ul>'.$list.'</ul>
                <br/>If you want to be able to bet for the above-mentionned pots, you have to pay the participation fee.
                Please contact the pot manager if you have already done so.'
            );
        }
        return $this->render('EUMainBundle:Index:index.html.twig');
    }

    public function mainpotAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Pot');
        $mainPot = $rep->find(1);
        $user = $this->getUser();
        $participation = new Participation();
        $participation->setUser($user);
        $participation->setPot($mainPot);
        $em->persist($participation);
        $em->flush();
        return $this->redirectToRoute('eu_main_homepage');
    }

    public function rulesAction()
    {
        return $this->render('EUMainBundle:Index:rules.html.twig');
    }

    public function testAction()
    {
        return $this->render('EUMainBundle:Index:tester.html.twig');
    }

}
