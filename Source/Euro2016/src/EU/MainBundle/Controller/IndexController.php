<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EU\MainBundle\Entity\Bet;
use EU\MainBundle\Entity\Game;
use EU\MainBundle\Entity\Team;
use EU\MainBundle\Entity\Pot;
use EU\MainBundle\Entity\Participation;
use EU\MainBundle\Entity\ResponseHelperControllerInterface;


class IndexController extends Controller implements ResponseHelperControllerInterface
{

    public function getDefaultTemplate()
    {
        return 'EUMainBundle:Index:index.html';
    }

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
                $list = $list.'<li><a href="pots/'.$p->getPot()->getId().'">'.$p->getPot()->getName().'</a>: '.$p->getPot()->getAmount().'&euro;</li>';
            }
            $this->get('session')->getFlashBag()->add(
                'info',
                '<h3>Participations unpaid</h3>Some of your participations in pots are unpaid:<ul>'.$list.'</ul>
                <br/>If you want to be able to bet for the above-mentionned pots, you have to pay the participation fee.
                Please contact the pot manager if you have already done so.'
            );
        }
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bets = $rep->findAll();
        $rep = $em->getRepository('EUMainBundle:Game');
        $games = $rep->findAll();
        $rep = $em->getRepository('ApplicationSonataUserBundle:User');
        $users = $rep->findAll();
        return $this->render('EUMainBundle:Index:index.html.twig', array(
            'bets'  => $bets,
            'games' => $games,
            'users' => $users
        ));
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
        $participation->setAcceptedAt(new \DateTime());
        $em->persist($participation);
        $em->flush();
        return $this->redirectToRoute('pots_list');
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
