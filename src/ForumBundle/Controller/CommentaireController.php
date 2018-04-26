<?php

namespace ForumBundle\Controller;

use MainBundle\Entity\jaime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use MainBundle\Entity\Commentaire_forum;
use MainBundle\Repository\Commentaire_forumRepository;

class CommentaireController extends Controller
{
    public function readAction(Request $request,$id,$iduser)
    {
        $em= $this->getDoctrine()->getManager();
        $voitures=$em->getRepository("MainBundle:Commentaire_forum")->findByIdPost($id);
        $voitures1=$em->getRepository("MainBundle:post")->findPost($id);
        $id_sujet=$em->getRepository("MainBundle:post")->find($id);
        $jaime=$em->getRepository("MainBundle:jaime")->findeverything($iduser,$id);
        $jaim=$em->getRepository("MainBundle:jaime")->findeverything1($id);
        $tout_les_jaimes=$em->getRepository("MainBundle:jaime")->findjaime($id);
        $calcule_jaime=$em->getRepository("MainBundle:jaime")->calcule_jaime($id);
        $sum = count($calcule_jaime);

        $voiture = new Commentaire_forum();
        $voiture->setPost($id_sujet);
        $user=$this->getUser();
        $voiture->setUser($user);


        $form=$this->createFormBuilder($voiture)

            ->add('contenu',TextType::class)
            ->add('dateCommentaire', DateTimeType::class)
            ->add('save',SubmitType::class)
            ->getForm();


        $form->handleRequest($request);
        if(($form->isSubmitted())&&($form->isValid()))
        {

            $em=$this->getDoctrine()->getManager();
            $voiture->setDateCommentaire(new \DateTime('now '));
            $em->persist($voiture);
            $em->flush();

            return $this->redirectToRoute("forum_commentaire",array('id'=>$id,'iduser'=>$iduser));
        }
        return $this->render('ForumBundle:forum:read_commentaire.html.twig', array('tout_les_jaimes'=>$tout_les_jaimes,
            "voitures"=>$voitures,"voitures1"=>$voitures1,'user'=>$user,
            'jaime'=>$jaime,'ids'=>$id,'jaim'=>$jaim,'sum'=>$sum ,"form"=>$form->createView()
        ));
    }

    public function updateAction(Request $request, $id,$ids,$iduser)
    {
        $em = $this->getDoctrine()->getManager();
        $modele = $em->getRepository(Commentaire_forum::class)->find($id);

        //Save?
        if ($request->isMethod('POST')) {
            //Mettre a jour
            $modele->setContenu($request->get('contenu'));
            $em->persist($modele);
            $em->flush();
            //Rederiger vers read
            return $this->redirectToRoute("forum_commentaire",array('id'=>$ids,'iduser'=>$iduser));

        }
        // Recuperation des donnees
        //Remplir form
        return $this->render('ForumBundle:Forum:update_commentaire.html.twig', array(
            'modele' => $modele
            // ...
        ));


    }


    public function deleteAction($id,$ids,$iduser)
    {
        $em = $this->getDoctrine()->getManager();
        $modele= $em->getRepository(Commentaire_forum::class)->find($id);
        $em->remove($modele);
        $em->flush();
        return $this->redirectToRoute("forum_commentaire",array('id'=>$ids,'iduser'=>$iduser));
    }

    public function jaimeAction($idsujet,$iduser)
    {
        $em= $this->getDoctrine()->getManager();

        $jaime = new jaime();
        $user=$this->getUser();
        $jaime->setUser($user);
        $sujet=$em->getRepository('MainBundle:Post')->find($idsujet);
        $jaime->setpost($sujet);
        $em->persist($jaime);
        $em->flush();
        return $this->redirectToRoute("forum_commentaire",array('id'=>$idsujet,'iduser'=>$iduser));

    }

    public function jaimepasAction($idsujet,$iduser)
    {
        $em= $this->getDoctrine()->getManager();
        $em->getRepository(jaime::class)->findedql($iduser);

        return $this->redirectToRoute("forum_commentaire",array('id'=>$idsujet,'iduser'=>$iduser));

    }

}
