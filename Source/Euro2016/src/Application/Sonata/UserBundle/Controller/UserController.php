<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EU\MainBundle\Entity\ResponseHelper;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ApplicationSonataUserBundle:User');
        $users = $rep->findAll();
        $response = new ResponseHelper($this, Response::HTTP_OK, $users);
        return $response->renderResponse();
    }

}
