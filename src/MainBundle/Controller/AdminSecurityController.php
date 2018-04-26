<?php

namespace MainBundle\Controller;

use FOS\UserBundle\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminSecurityController extends SecurityController
{
    protected function renderLogin(array $data)
    {
        return $this->render('@FOSUser/Security/loginAdmin.html.twig', $data);
    }

    public function adminAction()
    {
        return $this->render('@Main/Default/indexadmin.html.twig');
    }
}
