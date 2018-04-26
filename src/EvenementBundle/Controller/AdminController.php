<?php

namespace EvenementBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use EvenementBundle\Form\AvisType;
use EvenementBundle\Form\EvenementType;
use EvenementBundle\Form\EvenementTypeUpdate;
use MainBundle\Entity\Avis_evenement;
use MainBundle\Entity\Evenement;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class AdminController extends Controller
{
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
    public function AjouterEAction(Request $request)
    {
        $evenement = new Evenement();
        $form=$this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted())
        {
            $em=$this->getDoctrine()->getManager();
            $file = $evenement->getImageEve();
            $filename = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('brochures_directory'),$filename);
            $evenement->setImageEve($filename);
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
        $form=$this->createForm(EvenementTypeUpdate::class,$evenement);
        $form->handleRequest($request);
        if($form->isSubmitted())
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
    public function ReadAvisAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $avis=$em->getRepository("MainBundle:Avis_evenement")->findbyid($id);
        $evenement=$em->getRepository("MainBundle:Evenement")->find($id);
        return $this->render('@Evenement/Evenement/readmore.html.twig',array("avis"=>$avis,"evenement"=>$evenement,"id"=>$id));
    }
    public function DeleteAvisAdminAction($id,$ide)
    {
        $em = $this->getDoctrine()->getManager();
        $avis= $em->getRepository("MainBundle:Avis_evenement")->find($id);
        $evenement=$em->getRepository("MainBundle:Evenement")->find($ide);
        $em->remove($avis);
        $em->flush();
        return $this->redirectToRoute("read_avis",array("id"=>$ide));
    }
    public function statAction()
    {
        $m = $this->getDoctrine()->getManager();

        $avis = $m->getRepository('MainBundle:Avis_evenement')->findAll();
        $valeurs= array();
        $ids= array();
        foreach ($avis as $a)
        {
            array_push($valeurs, $a->getValeur());
            array_push($ids, $a->getUser()->getNom());
        }
        $bar = new BarChart();
        $bar->getData()->setArrayToDataTable([
            $ids,$valeurs
        ]);
        $bar->getOptions()->setTitle('Avis sur les évènements');
        $bar->getOptions()->getHAxis()->setTitle('Valeurs');
        $bar->getOptions()->getHAxis()->setMinValue(1);
        $bar->getOptions()->getHAxis()->setMaxValue(5);
        $bar->getOptions()->getVAxis()->setTitle('Utilisateurs');
        $bar->getOptions()->setWidth(900);
        $bar->getOptions()->setHeight(600);
        return $this->render('@Evenement/Evenement/stat.html.twig', array('barchart'=>$bar));
    }
}
