<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\ResponseHelper;
use EU\MainBundle\Entity\ResponseHelperControllerInterface;
use EU\MainBundle\Entity\SMS;
use EU\MainBundle\Entity\Game;

class SMSController extends Controller
{

    public function testAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $rep = $em->getRepository('EUMainBundle:Game');
        $game = $game = $rep->find(1);
        $sms = new SMS($user, $game);
        $em->persist($sms);
        $em->flush();
        $sms->send();
        $em->flush();
        return new Response('Hello world!');
    }

    public function statusCallBackAction()
    {
        $request = $this->getRequest();
        $id = $request->query->get('msgid');
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:SMS');
        $sms = $rep->find($id);
        $sms->setStatus($request->query->get('status'));
        $sms->setCost($request->query->get('cost'));
        $em->flush();
        return new Response('ok');
    }

    public function checkBetsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('EUMainBundle:Game');
        $game = $rep->findNextGames(1)[0];
        $now_2hours = (new \DateTime())->add(new \DateInterval('PT2H'));
        if($game->getStartTime() < $now_2hours)
        {
            $rep = $em->getRepository('ApplicationSonataUserBundle:User');
            $users = $rep->findAll();
            $rep = $em->getRepository('EUMainBundle:Bet');
            $nbr = 0;
            foreach ($users as $u)
            {
                $bet = $rep->findBy(array('user' => $u, 'game' => $game));
                if(!$bet)
                {
                    $nbr++;
                    $sms = new SMS($u, $game);
                    $em->persist($sms);
                    $em->flush();
                    $sms->send();
                    $em->flush();
                }
            }
            return new Response('ok sms: '.$nbr);
        }
        else
        {
            return new Response('no game');
        }
    }
}
