<?php

namespace EspaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('EspaceBundle:Default:index.html.twig');
    }

    }
