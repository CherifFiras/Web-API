<?php

namespace InteractionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('InteractionBundle:Default:index.html.twig');
    }
}
