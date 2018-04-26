<?php

namespace EvenementBundle\Controller;

use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use EvenementBundle\Form\AvisType;
use MainBundle\Entity\Avis_evenement;
use MainBundle\Entity\Evenement;
use MainBundle\Entity\Participation;
use MainBundle\MainBundle;
use MainBundle\Repository\ParticipationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ClientController extends Controller
{
    public function ReadEAction()
    {
        $em= $this->getDoctrine()->getManager();
        $evenements=$em->getRepository("MainBundle:Evenement")->findAll();
        foreach ($evenements as $evenement)
        {
            $date_now=new \DateTime();
            if($date_now>$evenement->getDateEvenement()){
                $avis=$em->getRepository("MainBundle:Avis_evenement")->findby(array("evenement"=>$evenement->getId()));
                $participations=$em->getRepository("MainBundle:Participation")->findby(array("evenement"=>$evenement->getId()));
                foreach ($avis as $avi)
                {
                    $em->remove($avi);
                    $em->flush();
                }
                foreach ($participations as $participation)
                {
                    $em->remove($participation);
                    $em->flush();
                }
                $em->remove($evenement);
                $em->flush();
            }
        }
        $eventplusvu=$em->getRepository("MainBundle:Evenement")->findPlusVu();
        $evenements=$em->getRepository("MainBundle:Evenement")->findbydate();
        return $this->render('EvenementBundle:Evenement:readclient.html.twig', array(
            "evenements"=>$evenements,
            "eventplusvu"=>$eventplusvu,
            "now"=>$date_now
        ));
    }
    public function ReadMoreECAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $Avis_evenements=$em->getRepository("MainBundle:Avis_evenement")->findbyid($id);
        $evenement=$em->getRepository("MainBundle:Evenement")->find($id);
        $commentaire_evenement = new Avis_evenement();
        $form=$this->createForm(AvisType::class,$commentaire_evenement);
        $form->handleRequest($request);
        if($form->isValid())
        {
            $user=$this->getUser();
            $commentaire_evenement->setUser($user);
            $commentaire_evenement->setEvenement($evenement);
            $em->persist($commentaire_evenement);
            $em->flush();
            return $this->redirectToRoute("readmoreclient_evenement",array('id'=>$id));
        }
        $evenement=$em->getRepository("MainBundle:Evenement")->find($id);
        $evenement->setVue($evenement->getVue()+1);
        $em->persist($evenement);
        $em->flush();
        return $this->render('@Evenement/Evenement/readmoreclient.html.twig', array(
            "evenement"=>$evenement,"id"=>$id,"Avis_evenements"=>$Avis_evenements,"form"=>$form->createView()

        ));
    }
    public function DeleteAvisAction($id,$ide)
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire_evenement= $em->getRepository("MainBundle:Avis_evenement")->find($id);
        $em->remove($commentaire_evenement);
        $em->flush();
        return $this->redirectToRoute("readmoreclient_evenement",array('id'=>$ide));
    }
    public function ParticipationAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $participation = new Participation();
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $evenement=$em->getRepository("MainBundle:Evenement")->find($id);
        $participTest = $this->getDoctrine()->getRepository("MainBundle:Participation")->findP($evenement,$user);
        if ($participTest == null ) {
            $evenement->setNbplaces($evenement->getNbplaces() - "1");
            $participation->setEvenement($evenement);
            $participation->setUser($user);
            $em->persist($participation);
            $em->flush();
            return $this->redirectToRoute('readmoreclient_evenement', array('id' => $id));
        }
        else {
            return $this->redirectToRoute('readmoreclient_evenement', array('id' => $id));
        }
    }

}
