<?php

namespace EU\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function getMenuAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            $user = $this->getUser();
        }
        else
        {
            $user = null;
        }
        return $this->render('EUMenuBundle:Menu:menu.html.twig', array('user' => $user));
    }
}
