<?php

namespace QB\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('QBTestBundle:Default:index.html.twig', array('name' => $name));
    }

    public function index2Action()
    {
        return $this->render('QBTestBundle:Default:index.html.twig', array('name' => 'Quentin'));
    }
}
