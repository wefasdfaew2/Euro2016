<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EU\MainBundle\Entity\Bet;
use EU\MainBundle\Entity\Game;
use EU\MainBundle\Entity\Team;
use EU\MainBundle\Entity\Pot;

class IndexController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $team1 = new Team();
        $team1->setShortName('BEL');
        $team1->setLongName('Belgique');
        //$em->persist($team1);
        $team2 = new Team();
        $team2->setShortName('FRA');
        $team2->setLongName('France');
        //$em->persist($team2);

        $game = new Game();
        $game->setStartTime(new \DateTime());
        $game->setTeam1($team1);
        $game->setTeam2($team2);
        //$em->persist($game);

        $pot = new Pot();
        $pot->setName('Test Pot');
        $pot->setAmount(5);
        $pot->setManager($this->getUser());
        $em->persist($pot);

        $bet = new Bet();
        $bet->setScore1(3);
        $bet->setScore2(0);
        $bet->setGame($game);
        $bet->setUser($this->getUser());
        $bet->setPot($pot);
        //$em->persist($bet);
        //$em->flush();
        //echo($bet);
        return $this->render('EUMainBundle:Index:index.html.twig');
    }

    public function testAction()
    {
        return $this->render('EUMainBundle:Index:tester.html.twig');
    }

}
