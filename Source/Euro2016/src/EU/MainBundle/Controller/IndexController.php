<?php

namespace EU\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        return $this->render('EUMainBundle:Index:index.html.twig');
    }

}
