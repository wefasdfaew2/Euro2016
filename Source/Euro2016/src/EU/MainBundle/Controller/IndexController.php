<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\Bet;
use EU\MainBundle\Entity\Game;
use EU\MainBundle\Entity\Team;
use EU\MainBundle\Entity\Pot;
use EU\MainBundle\Entity\Participation;
use EU\MainBundle\Entity\ResponseHelperControllerInterface;
use Stripe\Stripe;


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
        /*
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
        */
        if($user->getPhone() == '')
        {
            $this->get('session')->getFlashBag()->add(
                'warning',
                '<h3>Update your phone number</h3>
                It looks like you have not yet provided your phone number for the text reminders.
                Your phone number should only contain numbers (without 00 or +) and be in international format (e.g.: 32475123456).</br></br>
                <form class="form-inline" action="'.$this->generateUrl('eu_main_update_phone').'" method="POST">
                <div style="width: 250px" class="input-group">
                    <input type="number" class="form-control" name="phone" placeholder="32475123456"/>
                    <span class="input-group-btn">
                        <input type="submit" class="btn btn-primary" value="Save"/>
                    </span>
                </div>
                </form>
                '
            );
        }
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bets = $rep->getBetsForActivityFeed(10);
        $rep = $em->getRepository('EUMainBundle:Game');
        $games = $rep->findAll();
        $games_left = 0;
        foreach ($games as $g)
        {
            if(!$g->hasScore())
                $games_left++;
        }
        $rep = $em->getRepository('ApplicationSonataUserBundle:User');
        $users = $rep->findAll();
        $users_points = array();
        $user_points = 0;
        foreach($users as $u)
        {
            $points = $this->getPoints($u);
            $users_points[$u->getId()] = $points;
            if($u->getId() == $user->getId())
                $user_points = $points;
        }
        arsort($users_points);
        $rank = 0;
        foreach ($users_points as $key => $value)
        {
            $rank++;
            if($user->getId() == $key)
                break;
        }
        array_slice($users_points, 0, 8, true);
        $leaderboard = array();
        foreach ($users_points as $key => $value)
        {
            $leaderboard[$this->findUser($users, $key)->__toString()] = $value;
        }
        return $this->render('EUMainBundle:Index:index.html.twig', array(
            'bets'  => $bets,
            'games' => $games,
            'users' => $leaderboard,
            'user_points'   => $user_points,
            'user_rank'     => $rank,
            'games_left'    => $games_left,
            'money_pot'     => max((sizeof($users) - 2) * 20, 100)
        ));
    }

    private function getPoints($user)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Bet');
        $bets = $rep->findBy(array('user' => $user));
        $points = 0;
        foreach ($bets as $b)
        {
            if($b->getGame()->hasScore())
            {
                $points += $b->getPoints();
            }
        }
        return $points;
    }

    private function findUser($users, $id)
    {
        foreach ($users as $u)
        {
            if($u->getId() == $id)
                return $u;
        }
    }

    public function chargeAction()
    {
        $stripe = array(
          "secret_key"      => "sk_test_QfqDJzLloN8gMrsS8Vhmp1y4",
          "publishable_key" => "pk_test_SHKaOoxRZVBJPBOIf9RKU9tA"
        );

        Stripe::setApiKey($stripe['secret_key']);

        $request = $this->getRequest();
        $token  = $request->request->get('stripeToken');
        $user = $this->getUser();

        try
        {
            $charge = \Stripe\Charge::create(array(
                "amount" => 2250, // amount in cents, again
                "currency" => "eur",
                "source" => $token,
                "description" => $user->getId().' '.$user->getFirstname().' '.$user->getLastname()
            ));
            $em = $this->getDoctrine()->getManager();
            $rep = $em->getRepository('EUMainBundle:Pot');
            $mainPot = $rep->find(1);
            $participation = new Participation();
            $participation->setUser($user);
            $participation->setPot($mainPot);
            $participation->setAcceptedAt(new \DateTime());
            $participation->setPaidAt(new \DateTime());
            $em->persist($participation);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',
                '<h3>Congratulations!</h3><p>Your payment has been accepted and you can now start betting on the upcoming <a href="'.$this->generateUrl('games_list').'">games</a>.</p>'
            );
            return $this->redirectToRoute('eu_main_homepage');
        }
        catch(\Stripe\Error\Card $e)
        {
            return new Response('not ok');
        }

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
