<?php

namespace EU\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function getMenuAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        return $this->render('EUMenuBundle:Menu:menu.html.twig', array('user' => $user));
    }
}
