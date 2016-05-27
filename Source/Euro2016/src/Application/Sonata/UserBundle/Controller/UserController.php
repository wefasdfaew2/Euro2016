<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EU\MainBundle\Entity\ResponseHelper;
use Symfony\Component\HttpFoundation\Response;
use EU\MainBundle\Entity\ResponseHelperControllerInterface;

class UserController extends Controller implements ResponseHelperControllerInterface
{

    public function getDefaultTemplate()
    {
        return 'EUMainBundle:Game:index.html.twig';
    }

    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('ApplicationSonataUserBundle:User');
        $users = $rep->findAll();
        $response = new ResponseHelper($this, Response::HTTP_OK, $users);
        return $response->renderResponse();
    }

    public function updatePhoneAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $request = $this->getRequest();
        $phone = $request->request->get('phone');
        if(!is_numeric($phone) OR $phone =='')
        {
            $this->get('session')->getFlashBag()->add(
                'danger',
                '<h3>Error</h3>The phone number should only contain numbers (e.g.: 32475123456)!
                '
            );
        }
        else
        {
            $user->setPhone($phone);
            $em->flush();
            $this->get('session')->getFlashBag()->add(
                'success',
                'Your phone number has correctly been updated.'
            );
        }
        return $this->redirectToRoute('eu_main_homepage');
    }

}
