<?php

namespace EspaceBundle\Controller;

use blackknight467\StarRatingBundle\Form\RatingType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use MainBundle\Entity\Avis_espace;
use MainBundle\Entity\Commentaire_espace;
use MainBundle\Entity\Espace;
use MainBundle\Entity\Espace_copy;
use MainBundle\Entity\Photo_espace;
use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class EspaceController extends Controller
{
    public function readAction()
    {
        $em= $this->getDoctrine()->getManager();
        $espaces=$em->getRepository("MainBundle:Espace")->findetat();
        return $this->render('@Espace/Espace_admin/espace.html.twig', array(
            "espaces"=>$espaces

        ));
    }

    public function read_modifierAction()
    {
        $em= $this->getDoctrine()->getManager();
        $espaces=$em->getRepository(Espace_copy::class)->findAll();
        return $this->render('EspaceBundle:Espace_admin:espace_modifier_client.html.twig', array(
            "espaces"=>$espaces

        ));
    }
    public function afficherAction()
    {
        return $this->render('@Espace/Espace_client/espace_client.html.twig');
    }
    public function read_confirmerAction()
    {
        $em= $this->getDoctrine()->getManager();
        $espaces=$em->getRepository("MainBundle:Espace")->findetatnon();
        return $this->render('@Espace/Espace_admin/espace_confirmer.html.twig', array(
            "espaces"=>$espaces

        ));
    }
    public function confirmerAction($id)
    {
        $em= $this->getDoctrine()->getManager();
        $espaces=$em->getRepository("MainBundle:Espace")->find($id);
        $espaces->setEtat("1");
        $em->persist($espaces);
        $em->flush();
        return $this->redirectToRoute("espace_confirmer");
    }
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $espace=$em->getRepository("MainBundle:Espace")->find($id);
        $em->remove($espace);
        $em->flush();
        return $this->redirectToRoute("afficher_espace");
    }
    public function supprimer_commentaireAction($id){
        $em = $this->getDoctrine()->getManager();
        $commentaire=$em->getRepository("MainBundle:Commentaire_espace")->find($id);
        $em->remove($commentaire);
        $em->flush();
        return $this->redirectToRoute("afficher_espace");
    }
    public function deleteconfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $espace=$em->getRepository("MainBundle:Espace")->find($id);
        $em->remove($espace);
        $em->flush();
        return $this->redirectToRoute("espace_confirmer");
    }
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $espace=$em->getRepository("MainBundle:Espace")->find($id);


        $form=$this->createFormBuilder($espace)
            ->add('titre',TextType::class)
            ->add('description',TextType::class)
            ->add('adresse',TextType::class)
            ->add('file',FileType::class)

            ->add('Save',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if (($form->isSubmitted())&&($form->isValid()))
        {   $espace=$form->getData();
            $espace->upload();
            $em->persist($espace);
            $em->flush();
            return $this->redirectToRoute('afficher_espace');
        }
        // Recuperation des donnees
        //Remplir form
        return $this->render('@Espace/Espace_admin/modifier_espace.html.twig', array(
            "form"=>$form->createView()
            // ...
        ));


    }

    public function  confirmer_modificationAction($id)
    {
             $em=$this->getDoctrine()->getManager();
             $em1=$this->getDoctrine()->getManager();
              $espace_copy=$em->getRepository(Espace_copy::class)->find($id);
              $espace=$em->getRepository(Espace::class)->find($espace_copy->getIdEsp());
              $espace->setDescription($espace_copy->getDescription());
              $espace->setTitre($espace_copy->getTitre());
              $em->persist($espace);
              $em1->remove($espace_copy);
              $em1->flush();
              $em->flush();
              return $this->redirectToRoute("espace_modifier_client");

        }
    public function modifierparclientAction(Request $request, $id_esp)
    {     $em=$this->getDoctrine()->getManager();
          $espace_copy = new Espace_copy();
        if($request->isMethod('post')){
           $espace_copy->setDescription($request->get('description'));
           $espace_copy->setTitre($request->get('titre'));
           $espace_copy->setNom($request->get('nom'));
           $espace_copy->setPrenom($request->get('prenom'));
           $espace_copy->setIdEsp($id_esp);
            $em->persist($espace_copy);
            $em->flush();
            return $this->redirectToRoute("offre_espace");
        }
        return $this->redirectToRoute("offre_espace");
    }

    public function modifierespaceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $espace=$em->getRepository("MainBundle:Espace")->find($id);


        $form=$this->createFormBuilder($espace)
            ->add('titre',TextType::class)
            ->add('description',TextType::class)
            ->add('adresse',TextType::class)
            ->add('file',FileType::class)
            ->add('Save',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);

        if (($form->isSubmitted())&&($form->isValid()))
        {   $espace=$form->getData();
            $espace->upload();
            $em->persist($espace);
            $em->flush();
            return $this->redirectToRoute('info_espace');
        }
        // Recuperation des donnees
        //Remplir form
        return $this->render('@Espace/Espace_client/modifierespaceinter.html.twig', array(
            "form"=>$form->createView()
            // ...
        ));


    }
    public function RechercheAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            $search = $request->query->get('espace');
            $en = $this->getDoctrine()->getManager();
            $espaces = $en->getRepository("MainBundle:Espace")->find($search);
            $serializer = new Serializer(array(new ObjectNormalizer()));
            $data = $serializer->normalize($espaces);
            return new JsonResponse($data);

        }
        return $this->render("@Espace/Espace_admin/espace.html.twig");
    }
    public function createAction(Request $request,$titre,$description,$iduser,$adresse)
    {
        $em=$this->getDoctrine()->getManager();
        $user=$em->getRepository(User::class)->find($iduser);

        $espace= new Espace();



            $espace->setEtat("0");
            $espace->setLat(33.888077);
            $espace->setLongi(10.097522);
            $espace->setTitre($titre);
            $espace->setDescription($description);
            $espace->setAdresse($adresse);
            $espace->setPhoto("barry-allen-the-flash-logo-3.jpg");
            $espace->setUser($user);
            $em->persist($espace);
            $em->flush();
        return $this->redirectToRoute("offre_espace");




    }
    public function checkAction($iduser,$idesp)
    {
        $em=$this->getDoctrine()->getManager();

        $user=$em->getRepository("MainBundle:User")->find($iduser);

        $espace=$em->getRepository("MainBundle:Espace")->find($idesp);
        $rati = $em->getRepository("MainBundle:Avis_espace")->check($user->getId(),$espace->getId());

            $serializer= new Serializer([new ObjectNormalizer()]);
            $data=$serializer->normalize($rati);

            return new JsonResponse($data);




    }

    public function albumAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $espace=$em->getRepository("MainBundle:Espace")->find($id);
        $album = new Photo_espace();
        $form=$this->createFormBuilder($album)
            ->add('file4',FileType::class)
            ->add('file1',FileType::class)
            ->add('file2',FileType::class)
            ->add('file3',FileType::class)
            ->add('Enregistrer',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if (($form->isSubmitted())&&($form->isValid()))
        {   $album=$form->getData();
        $album->setEspace($espace);
            $album->upload();
            $em->persist($album);
            $em->flush();
            return $this->redirectToRoute('offre_espace');
        }
        return $this->render('@Espace/Espace_client/ajouter_album_espace.html.twig',array(
                "form"=>$form->createView()
            )
        );



    }
    public function ajouter_commentaireAction(Request $request,$id,$idus,$contenu)
    {

        $em=$this->getDoctrine()->getManager();
        $commentaire= new Commentaire_espace();

        $espaces=$em->getRepository(Espace::class)->find($id);
        $userr = $em->getRepository(User::class)->find($idus);
           $commentaire->setContenu($contenu);
           $commentaire->setUser($userr);
           $commentaire->setEspace($espaces);
            $commentaire->setDateCommentaire(new \DateTime('now +1hour'));
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute("offre_espace");




    }
    public function Liste_espaceAction()
    {
        $em= $this->getDoctrine()->getManager();
        $user=$this->container->get('security.token_storage')->getToken()->getUser();
        $espaces=$em->getRepository("MainBundle:Espace")->findetat();
        $serializer= new Serializer([new ObjectNormalizer()]);
        $data=$serializer->normalize($espaces,null, array('attributes' => array('id','titre','description','adresse','photo','etat','longi','lat','email','sender'=>['id','titre','description','adresse','photo','etat','longi','lat','email'])));
        return new JsonResponse($data);

    }
    public function infoespaceAction(Request $request,$id)
    {
        $em= $this->getDoctrine()->getManager();
        $espaces=$em->getRepository("MainBundle:Espace")->find($id);

        $user=$this->container->get('security.token_storage')->getToken()->getUser();
        $commentaire=$em->getRepository(Commentaire_espace::class)->findcom($id);
        $album=$em->getRepository("MainBundle:Photo_espace")->findalbum($id);
        $rating=$em->getRepository("MainBundle:Avis_espace")->findrati($id);

        $array = array("commentaire"=>$commentaire,"espace"=>$espaces);
        $normalizer = new ObjectNormalizer();
        $serializer= new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($array,null, array('attributes' => array('id','titre','description','adresse','photo','etat','longi','lat','contenu','dateCommentaire','user'=>['id','nom','prenom'])));
        //$array=$serializer->normalize($commentaire,null, array('attributes' => array('id','contenu','dateCommentaire')));

        return new JsonResponse($data);
    }

    public function ratingAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $ratings=$em->getRepository("MainBundle:Avis_espace")->findrat($id);
        $serializer= new Serializer([new ObjectNormalizer()]);
        $data=$serializer->normalize($ratings);
        return new JsonResponse($data);

    }

    public function ajout_ratingAction(Request $request,$idesp,$ratingg,$iduser)
    {
        $em=$this->getDoctrine()->getManager();
        $rating = new Avis_espace();
        $esp=$em->getRepository("MainBundle:Espace")->find($idesp);

        $user=$em->getRepository("MainBundle:User")->find($iduser);
        $rating->setEspace($esp);
        $rating->setUser($user);
        $rating->setNbrating(1);
        $rating->setRating($ratingg);
        $em->persist($rating);
        $em->flush();
        return $this->redirectToRoute("offre_espace");

    }

    public function ajaxSnippetImageSendAction(Request $request)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");
        $document = new Photo_espace();
        $document->setEspace($id);
        $document->upload();
        $em->persist($document);
        $em->flush();

        //infos sur le document envoyé
        //var_dump($request->files->get('file'));die;
        return new JsonResponse(array('success' => true));
    }
    public function statAction()
    {
        $m = $this->getDoctrine()->getManager();

        $avis = $m->getRepository('MainBundle:Espace')->findAll();
        $valeurs= array();
        $ids= array();
        foreach ($avis as $a)
        {  if($a->getRating()>0) {
            $as = $a->getRating() / $a->getNbrating();
        }else{
            $as= $a->getRating();
        }
            array_push($valeurs, $as);
            array_push($ids, $a->getTitre());
        }
        $bar = new BarChart();
        $bar->getData()->setArrayToDataTable([
            $ids,$valeurs
        ]);
        $bar->getOptions()->setTitle('Rating des espaces');
        $bar->getOptions()->getHAxis()->setTitle('Valeurs');
        $bar->getOptions()->getHAxis()->setMinValue(1);
        $bar->getOptions()->getHAxis()->setMaxValue(4);
        $bar->getOptions()->getVAxis()->setTitle('Espace');
        $bar->getOptions()->setWidth(900);
        $bar->getOptions()->setHeight(600);
        return $this->render('@Espace/Espace_admin/stat.html.twig', array('barchart'=>$bar));
    }

}
