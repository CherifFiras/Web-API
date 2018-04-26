<?php

namespace ForumBundle\Controller;

use MainBundle\Entity\Post;
use MainBundle\Entity\User;
use MainBundle\MainBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use MainBundle\Entity\Categorie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class CategorieController extends Controller
{



    public function ForumAction(Request $request)

    {
        $em= $this->getDoctrine()->getManager();

        $plus=$em->getRepository("MainBundle:Categorie")->findplus();
        $recent=$em->getRepository("MainBundle:Categorie")->findRecent();
        $categorie = $request->get('categorie');
        $voitures = $em->getRepository("MainBundle:Categorie")->rechercheMarque($categorie);
        $paginator  = $this->get('knp_paginator');
        $voitures = $paginator->paginate(
            $voitures,
            $request->query->getInt('page', 1),
            4);
        return $this->render('ForumBundle:forum:categorie.html.twig', array(
            "voitures"=>$voitures,"recent"=>$recent,"plus"=>$plus
        ));
    }



    public function rechercheAction(){
        $request= $this->get('request_stack')->getCurrentRequest();
        $em = $this->getDoctrine()->getManager();
        if($request->getMethod()=='POST'){

            $libelle=$request->get('libelle');
            $voitures=$em->getRepository("MainBundle:Categorie")->findBy(array('libelle'=>$libelle));
            return $this->render("ForumBundle:Forum:categorie.html.twig",array('voitures'=>$voitures));
        }
        $voitures=$em->getRepository("MainBundle:Categorie")->findAll();
        return $this->render('ForumBundle:forum:categorie.html.twig', array(
            "voitures"=>$voitures

        ));
    }

    public function Calcule_sujetAction($id){

        $em = $this->getDoctrine()->getManager();
        $modele= $em->getRepository("MainBundle:Post")->findcalcule($id);
        return $modele;

    }

    public function Forum_ordre_DESCAction()
    {
        $em= $this->getDoctrine()->getManager();
        $request= $this->get('request_stack')->getCurrentRequest();

        if ($request->getMethod()=='POST' )
        {

                $libelle=$request->get('libelle');
                $recent=$em->getRepository("MainBundle:Categorie")->findRecent();
                $plus=$em->getRepository("MainBundle:Categorie")->findplus();

                $voitures=$em->getRepository("MainBundle:Categorie")->findBy(array('libelle'=>$libelle));
            $paginator  = $this->get('knp_paginator');
            $voitures = $paginator->paginate(
                $voitures,
                $request->query->getInt('page', 1),
                3);


                return $this->render("ForumBundle:Forum:categorie.html.twig",array('voitures'=>$voitures,"recent"=>$recent,"plus"=>$plus));

        }
        $plus=$em->getRepository("MainBundle:Categorie")->findplus();
        $recent=$em->getRepository("MainBundle:Categorie")->findRecent();
        $voitures=$em->getRepository("MainBundle:Categorie")->findparordre();
        $paginator  = $this->get('knp_paginator');
        $voitures = $paginator->paginate(
            $voitures,
            $request->query->getInt('page', 1),
            3);

        return $this->render('ForumBundle:forum:categorie.html.twig', array(
            "voitures"=>$voitures,"recent"=>$recent,"plus"=>$plus

        ));
    }

    public function Forum_ordre_ASCAction()

    {

        $em= $this->getDoctrine()->getManager();
        $request= $this->get('request_stack')->getCurrentRequest();

        if ($request->getMethod()=='POST' )
        {

            $libelle=$request->get('libelle');
            $recent=$em->getRepository("MainBundle:Categorie")->findRecent();
            $plus=$em->getRepository("MainBundle:Categorie")->findplus();
            $voitures=$em->getRepository("MainBundle:Categorie")->findBy(array('libelle'=>$libelle));
            $paginator  = $this->get('knp_paginator');
            $voitures = $paginator->paginate(
                $voitures,
                $request->query->getInt('page', 1),
                3);

            return $this->render("ForumBundle:Forum:categorie.html.twig",array('voitures'=>$voitures,"recent"=>$recent,"plus"=>$plus));

        }
        $plus=$em->getRepository("MainBundle:Categorie")->findplus();
        $recent=$em->getRepository("MainBundle:Categorie")->findRecent();
        $voitures=$em->getRepository("MainBundle:Categorie")->findparordreASC();
        $paginator  = $this->get('knp_paginator');
        $voitures = $paginator->paginate(
            $voitures,
            $request->query->getInt('page', 1), 3);

        return $this->render('ForumBundle:forum:categorie.html.twig', array(
            "voitures"=>$voitures,"recent"=>$recent,"plus"=>$plus

        ));
    }
    public function Forum_par_alAction()

    {

        $em= $this->getDoctrine()->getManager();
        $request= $this->get('request_stack')->getCurrentRequest();

        if ($request->getMethod()=='POST' )
        {

            $libelle=$request->get('libelle');
            $recent=$em->getRepository("MainBundle:Categorie")->findRecent();
            $plus=$em->getRepository("MainBundle:Categorie")->findplus();
            $voitures=$em->getRepository("MainBundle:Categorie")->findBy(array('libelle'=>$libelle,"plus"=>$plus));
            $paginator  = $this->get('knp_paginator');
            $voitures = $paginator->paginate(
                $voitures,
                $request->query->getInt('page', 1),
                3);

            return $this->render("ForumBundle:Forum:categorie.html.twig",array('voitures'=>$voitures,"recent"=>$recent));

        }
        $plus=$em->getRepository("MainBundle:Categorie")->findplus();
        $recent=$em->getRepository("MainBundle:Categorie")->findRecent();
        $voitures=$em->getRepository("MainBundle:Categorie")->find_par_al();
        $paginator  = $this->get('knp_paginator');
        $voitures = $paginator->paginate(
            $voitures,
            $request->query->getInt('page', 1),
            3);

        return $this->render('ForumBundle:forum:categorie.html.twig', array(
            "voitures"=>$voitures,"recent"=>$recent,"plus"=>$plus

        ));
    }

}
