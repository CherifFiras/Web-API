<?php

namespace EvenementBundle\Controller;

use EvenementBundle\Form\EvenementType;
use MainBundle\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function AjouterEAction(Request $request)
    {
        $evenement = new Evenement();
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();
            return $this->redirectToRoute("read_evenement");
        }
        return $this->render('EvenementBundle:Evenement:create.html.twig', array(
            "form"=>$form->createView()
        ));
    }

    public function ReadAdminAction()
    {
        $em= $this->getDoctrine()->getManager();
        $evenements=$em->getRepository("MainBundle:Evenement")->findAll();
        return $this->render('@Evenement/Evenement/read.html.twig', array(
            "evenements"=>$evenements

        ));
    }

    public function UpdateEAction ($id,Request $request)
    {
        $em= $this->getDoctrine()->getManager();
        $evenement=$em->getRepository("MainBundle:Evenement")->find($id);
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $em->flush();
            return $this->redirectToRoute("read_evenement");
        }
        return $this->render('EvenementBundle:Evenement:update.html.twig', array(
            "form"=>$form->createView()

        ));
    }
    public function DeleteEAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement= $em->getRepository("MainBundle:Evenement")->find($id);
        $em->remove($evenement);
        $em->flush();
        return $this->redirectToRoute("read_evenement");
    }

    public function ReadMoreEAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $evenement=$em->getRepository("MainBundle:Evenement")->find($id);
        return $this->render('EvenementBundle:Evenement:readmore.html.twig', array(
            "evenement"=>$evenement

        ));
    }
}
