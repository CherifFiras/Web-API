<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $user= $this->container->get('security.token_storage')->getToken()->getUser();
        if (in_array("ROLE_SUPER_ADMIN", $user->getRoles())) {
            return $this->redirectToRoute("admin");
        }
        else
        {
            return $this->render('MainBundle:Default:index.html.twig');
        }
    }

    public function ForumAction()
    {
        return $this->render('MainBundle:Default:forum.html.twig');
    }


    public function EspacesAction()
    {
        return $this->render('MainBundle:Default:espaces.html.twig');
    }


}
