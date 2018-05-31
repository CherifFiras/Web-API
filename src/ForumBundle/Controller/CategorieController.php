<?php

namespace ForumBundle\Controller;

use MainBundle\Entity\Commentaire_forum;
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
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class CategorieController extends Controller
{



    public function serviceAction()
    {
        $em= $this->getDoctrine()->getManager();
        $categorie = $em->getRepository("MainBundle:Categorie")->findAll();
        $normalizer = new ObjectNormalizer();
        //$normalizer->setIgnoredAttributes(array('user'));
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $dataarray = array("cat"=>$categorie);
        $data=$serializer->normalize($categorie, null, array('attributes' => array('id','description','libelle','image')));
        return new JsonResponse($data);
    }


    public function service2Action($idc)
    {

        $em= $this->getDoctrine()->getManager();
        $voitures=$em->getRepository("MainBundle:Post")->findByIdCategorie($idc);

        $normalizer = new ObjectNormalizer();
        //$normalizer->setIgnoredAttributes(array('user'));
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));

        $data=$serializer->normalize($voitures, null, array('attributes' => array('id','titre','contenu','image')));
        return new JsonResponse($data);
    }

    public function service3Action($idc)
    {
        $em= $this->getDoctrine()->getManager();
        $voitures=$em->getRepository("MainBundle:Post")->findByIdCategorie1($idc);

        $normalizer = new ObjectNormalizer();
        //$normalizer->setIgnoredAttributes(array('user'));
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));

        $data=$serializer->normalize($voitures, null, array('attributes' => array('id','titre','contenu','image')));
        return new JsonResponse($data);
    }


    public function service1Action($firstname , $password)
    {
        $em= $this->getDoctrine()->getManager();
     $user = new User();
     $user->setUsername($firstname);
        $user->setPassword($password);
        $user->setEmail($password);
        $user->setNom($password);
        $user->setPrenom($password);
        $em->persist($user);
        $em->flush();
        return new JsonResponse("OK");
    }

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

    public function ajouter_commentaireAction($idu , $ids,$cont)
    {
        $em= $this->getDoctrine()->getManager();
        $v = new Commentaire_forum();
        $voiture=$em->getRepository("MainBundle:User")->find($idu);
        $voitures=$em->getRepository("MainBundle:Post")->find($ids);
        $v->setUser($voiture);
        $v->setPost($voitures);
        $v->setContenu($cont);
        $em->persist($v);
        $em->flush();
        return new JsonResponse("ajouté");
    }

    public function commentaire_mobileAction($ids)
    {

        $em= $this->getDoctrine()->getManager();
        $voitures=$em->getRepository("MainBundle:Commentaire_forum")->findbyidsujet($ids);

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        //$normalizer->setIgnoredAttributes(array('user'));
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($voitures, null, array('attributes' => array('id','contenu','user')));
        return new JsonResponse($data);


    }

    public function forgotAction($email)
    {

        $em= $this->getDoctrine()->getManager();
        $voitures=$em->getRepository("MainBundle:User")->find_user($email);

        $normalizer = new ObjectNormalizer();
        //$normalizer->setIgnoredAttributes(array('user'));
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($voitures, null, array('attributes' => array('id','username','pass')));

        return new JsonResponse($data);


    }

    public function mobile_calculeAction($ids)
    {

        $em= $this->getDoctrine()->getManager();
        $voitures=$em->getRepository("MainBundle:Commentaire_forum")->calc($ids);

        $normalizer = new ObjectNormalizer();
        //$normalizer->setIgnoredAttributes(array('user'));
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($voitures, null, array('attributes' => array('id')));
        return new JsonResponse($data);


    }

}
