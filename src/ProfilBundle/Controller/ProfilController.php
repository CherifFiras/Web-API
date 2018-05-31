<?php

namespace ProfilBundle\Controller;

use MainBundle\Entity\Album;
use MainBundle\Entity\CentreInteret;
use MainBundle\Entity\Emploi;
use MainBundle\Entity\Loisir;
use MainBundle\Entity\Publication;
use MainBundle\Entity\Scolarite;
use MainBundle\Entity\Signaler;
use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProfilController extends Controller
{
    public function indexAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        if (in_array("ROLE_SUPER_ADMIN", $u->getRoles())) {
            return $this->redirectToRoute("admin");
        }
        $em = $this->getDoctrine()->getManager();
        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'livre'));
        $photos = $em->getRepository(Album::class)->findBy(array('user' => $u->getId()),null,9,null);

        //$users_sug = $em->getRepository(User::class)->findBy(array('salt' => $u->getSalt()),null,6,null);
        $users_sug = $em->getRepository(User::class)->findSuggestionUsers("ROLE_SUPER_ADMIN",$u->getSalt(),$u->getId());
        //------------------------------
        $pubs = $em->getRepository(Publication::class)->findBy(array('user' => $u->getId()),array('datePublication' => 'DESC'));

        if ($request->isMethod('POST')) {
            if ($request->request->has('idpubd')) {
                $p= $em->getRepository(Publication::class)->find($request->get("idpubd"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute("profil_homepage");
            }
            if ($request->request->has('idpubmodal')) {
                $p= $em->getRepository(Publication::class)->find($request->get("idpubmodal"));
                $p->setContenu(($request->get('contenuup')));
                $d = new \DateTime("now");
                $p->setDatePublication($d);
                $em->persist($p);
                $em->flush();
                return $this->redirectToRoute("profil_homepage");
            }
            if ($request->request->has('contenuajout')) {
                $p = new Publication();
                $p->setContenu(($request->get('contenuajout')));
                $d = new \DateTime("now");
                $p->setDatePublication($d);
                $p->setUser($u);
                $em->persist($p);
                $em->flush();
            }
            return $this->redirectToRoute('profil_homepage');
        }
        //------------------------------
        return $this->render('ProfilBundle:Default:profil.html.twig', array(
            'iduser' => $u->getId(),'curr_user' => $u,'films'=>$films,'series'=>$series,'artists'=>$artists,'livres'=>$livres,
            'photos'=>$photos,'pubs'=>$pubs,'users_sug'=>$users_sug
        ));
    }

    public function paramsInfoAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        //----------------
        $form=$this->createFormBuilder($u)
            ->add('imageFile', VichImageType::class)
            ->add('Ajouter',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if (($form->isSubmitted())&&($form->isValid()))
        {
            $u=$form->getData();
            $em->flush();
            return $this->redirectToRoute('paramInfo');
        }
        //----------------
        $user = $em->getRepository(User::class)->find($u->getId());
        //Save?
        if ($request->isMethod('POST')) {
            //Mettre a jour
            $user->setNom($request->get('nom'));
            $user->setPrenom($request->get('prenom'));
            $user->setGenre($request->get('gen'));
            $d = new \DateTime($request->get('datetimepicker'));
            $user->setDateNaissance($d);
            $user->setPays($request->get('pays'));
            $user->setVille($request->get('ville'));
            $user->setRegion($request->get('region'));
            $user->setTel($request->get('tel'));
            $user->setPlaceNaiss($request->get('placenaiss'));
            $user->setRelegion($request->get('rel'));
            $user->setApropos($request->get('apropos'));
            $user->setFacebook($request->get('facebook'));
            $user->setTwitter($request->get('twitter'));
            $user->setInstagram($request->get('instagram'));
            $user->setOccupation($request->get('occ'));
            //$user->setPhotoprofil("unknownphoto.jpg");
            //----------------------PhotoUpload

            //--------------------------------
            $em->persist($user);
            $em->flush();
            //Rederiger vers read
            return $this->redirectToRoute('paramInfo');
        }
        // Recuperation des donnees
        //Remplir form


        return $this->render('ProfilBundle:Default:paraminfo.html.twig', array(
            'iduser' => $u->getId(),'us' => $u,'form'=>$form->createView()
        ));

    }

    public function centreintAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        //-----------------Afficher les centres d'interet
        $em = $this->getDoctrine()->getManager();
        $cen_user_film = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'film'));
        $cen_user_serie = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'serie'));
        $cen_user_livre = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'livre'));
        $cen_user_artist = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'artist'));
        $loisir = $em->getRepository(Loisir::class)->findBy(array('user' => $u->getId()));

        $newc = new CentreInteret();
        //$newl = new Loisir();
        if ($request->isMethod('POST'))
        {
            if ($request->request->has('idc')) {
                $delc= $em->getRepository(CentreInteret::class)->find($request->get("idc"));
                $em->remove($delc);
                $em->flush();
                return $this->redirectToRoute("centreinteret");
            }
            if ($request->request->has('newcentrefilm'))
            {
                $newc->setType($request->get("type"));
                $newc->setContenu($request->get("newcentrefilm"));
                $newc->setUser($u);
                $em->persist($newc);
                $em->flush();
                return $this->redirectToRoute('centreinteret');
            }
            if ($request->request->has('newcentreserie'))
            {
                $newc->setType($request->get("type"));
                $newc->setContenu($request->get("newcentreserie"));
                $newc->setUser($u);
                $em->persist($newc);
                $em->flush();
                return $this->redirectToRoute('centreinteret');
            }
            if ($request->request->has('newcentrelivre'))
            {
                $newc->setType($request->get("type"));
                $newc->setContenu($request->get("newcentrelivre"));
                $newc->setUser($u);
                $em->persist($newc);
                $em->flush();
                return $this->redirectToRoute('centreinteret');
            }
            if ($request->request->has('newcentreartist'))
            {
                $newc->setType($request->get("type"));
                $newc->setContenu($request->get("newcentreartist"));
                $newc->setUser($u);
                $em->persist($newc);
                $em->flush();
                return $this->redirectToRoute('centreinteret');
            }
            if ($request->request->has('newlois'))
            {
                $list = $request->get("newloisir");
                foreach ($list as $selectedOption){
                    $newloisir = new Loisir();
                    $newloisir->setContenu($selectedOption);
                    $newloisir->setUser($u);
                    $em->persist($newloisir);
                    $em->flush();
                }
                return $this->redirectToRoute('centreinteret');
            }
            if ($request->request->has('idl')) {
                $dell= $em->getRepository(Loisir::class)->find($request->get("idl"));
                $em->remove($dell);
                $em->flush();
                return $this->redirectToRoute("centreinteret");
            }
        }

        //-----------------
        return $this->render('ProfilBundle:Default:centreinteret.html.twig', array(
            'iduser' => $u->getId(),'centresfilm' => $cen_user_film,'centresserie' => $cen_user_serie,
            'centreslivre'=>$cen_user_livre,'centreartist'=>$cen_user_artist,'loisir'=>$loisir
        ));
    }

    public function eduEmpAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $scol = $em->getRepository(Scolarite::class)->findBy(array('user' => $u->getId()),array('dateDebut' => 'ASC'));
        $empl = $em->getRepository(Emploi::class)->findBy(array('user' => $u->getId()),array('dateDebut' => 'ASC'));


        //-----------------------
        if ($request->isMethod('POST'))
        {
            if ($request->request->has('idscol')) {
                $delscol= $em->getRepository(Scolarite::class)->find($request->get("idscol"));
                $em->remove($delscol);
                $em->flush();
                return $this->redirectToRoute("educationemploi");
            }
            if ($request->request->has('nometab'))
            {
                $newsc = new Scolarite();
                $newsc->setContenu($request->get("nometab"));
                $newsc->setDateDebut($request->get("datedeb"));
                $newsc->setDateFin($request->get("datefin"));
                $newsc->setUser($u);
                $em->persist($newsc);
                $em->flush();
                return $this->redirectToRoute('educationemploi');
            }
            if ($request->request->has('nomemp'))
            {
                $newem = new Emploi();
                $newem->setContenu($request->get("nomemp"));
                $newem->setDateDebut($request->get("datedebe"));
                $newem->setDateFin($request->get("datefine"));
                $newem->setUser($u);
                $em->persist($newem);
                $em->flush();
                return $this->redirectToRoute('educationemploi');
            }
            if ($request->request->has('idemp')) {
                $delemp= $em->getRepository(Emploi::class)->find($request->get("idemp"));
                $em->remove($delemp);
                $em->flush();
                return $this->redirectToRoute("educationemploi");
            }


        }
        //-----------------------
        return $this->render('ProfilBundle:Default:educationemploi.html.twig', array(
            'iduser' => $u->getId(),'scolarite' => $scol,'emploi'=>$empl
        ));
    }

    public function updateEmpAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get("idemp");
        $emp = $em->getRepository(Emploi::class)->find($id);

        if ($request->isMethod('POST')) {
            if ($request->request->has('idemp')) {
                $emp->setContenu(($request->get('contenu')));
                $emp->setDateDebut(($request->get('datde')));
                $emp->setDateFin(($request->get('datfe')));
                $em->persist($emp);
                $em->flush();
            }
            return $this->redirectToRoute('educationemploi');

        }
        return $this->redirectToRoute('educationemploi');
    }

    public function updateScolAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get("idscol");
        $sco = $em->getRepository(Scolarite::class)->find($id);

        if ($request->isMethod('POST')) {
            if ($request->request->has('idscol')) {
                $sco->setContenu(($request->get('contenu')));
                $sco->setDateDebut(($request->get('datds')));
                $sco->setDateFin(($request->get('datfs')));
                $em->persist($sco);
                $em->flush();
            }
            return $this->redirectToRoute('educationemploi');

        }
        return $this->redirectToRoute('educationemploi');
    }

    public function albumAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $album = new Album();
        //----------------
        $form=$this->createFormBuilder($album)
            ->add('imageFile', VichImageType::class)
            ->add('user', HiddenType::class, array('data' => $u))
            ->add('Ajouter',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if (($form->isSubmitted())&&($form->isValid()))
        {
            $album=$form->getData();
            $album->setUser($u);
            $em->persist($album);
            $em->flush();
            return $this->redirectToRoute('album');
        }
        //-------------------supprimer photo
        if ($request->isMethod('POST')) {
            if ($request->request->has('idp')) {
                $p= $em->getRepository(Album::class)->find($request->get("idp"));
                $em->remove($p);
                $em->flush();
                return $this->redirectToRoute("album");
            }
            return $this->redirectToRoute('album');
        }
        //----------------------------------
        $photos=$em->getRepository(Album::class)->findBy(array('user' => $u->getId()),array('datePublication' => 'ASC'));

        return $this->render('ProfilBundle:Default:album.html.twig', array(
            'curr_user' => $u,'form'=>$form->createView(),'photos'=>$photos
        ));
    }

    public function AproposAction()
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'livre'));
        $loisirs = $em->getRepository(Loisir::class)->findBy(array('user' => $u->getId()));
        $emplois = $em->getRepository(Emploi::class)->findBy(array('user' => $u->getId()),array('dateDebut' => 'ASC'));
        $scolarite = $em->getRepository(Scolarite::class)->findBy(array('user' => $u->getId()),array('dateDebut' => 'ASC'));

        return $this->render('ProfilBundle:Default:apropos.html.twig', array(
            'iduser' => $u->getId(),'curr_user' => $u,'films'=>$films,'series'=>$series,'loisirs'=>$loisirs,'artists'=>$artists,'livres'=>$livres,
            'emplois'=>$emplois,'scolarites'=>$scolarite
        ));
    }

    public function autreIndexAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'livre'));
        $photos = $em->getRepository(Album::class)->findBy(array('user' => $id),null,9,null);
        $pubs = $em->getRepository(Publication::class)->findBy(array('user' => $id),array('datePublication' => 'DESC'));
        //------------------------------
        if ($request->isMethod('POST')) {
            if ($request->request->has('idautreprofil')) {
                $user_signal = new Signaler();
                $user_signal->setCause(($request->get('ncontenusignal')));
                $user_signal->setIdUser(($request->get('idautreprofil')));
                $em->persist($user_signal);
                $em->flush();
                $idautreuser = ($request->get('idautreprofil'));
                $lastautreuser = $em->getRepository(User::class)->findBy(array('id' => $idautreuser));
                return $this->redirectToRoute("autre_profil_homepage", array('id' => $lastautreuser[0]->getId()));
            }
        }
        //------------------------------
        return $this->render('ProfilBundle:Default:autreProfil.html.twig', array(
            'autreUser' => $u[0],'films'=>$films,'series'=>$series,'artists'=>$artists,'livres'=>$livres,
            'photos'=>$photos,'pubs'=>$pubs
        ));
    }

    public function autreAproposAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $id,'type' => 'livre'));
        $loisirs = $em->getRepository(Loisir::class)->findBy(array('user' => $id));
        $emplois = $em->getRepository(Emploi::class)->findBy(array('user' => $id),array('dateDebut' => 'ASC'));
        $scolarite = $em->getRepository(Scolarite::class)->findBy(array('user' => $id),array('dateDebut' => 'ASC'));
        //------------------------------
        if ($request->isMethod('POST')) {
            if ($request->request->has('idautreprofil')) {
                $user_signal = new Signaler();
                $user_signal->setCause(($request->get('ncontenusignal')));
                $user_signal->setIdUser(($request->get('idautreprofil')));
                $em->persist($user_signal);
                $em->flush();
                $idautreuser = ($request->get('idautreprofil'));
                $lastautreuser = $em->getRepository(User::class)->findBy(array('id' => $idautreuser));
                return $this->redirectToRoute("autre_profil_apropos", array('id' => $lastautreuser[0]->getId()));
            }
        }
        //-----------------------------------------------
        return $this->render('ProfilBundle:Default:autreApropos.html.twig', array(
            'autreUser' => $u[0],'films'=>$films,'series'=>$series,'loisirs'=>$loisirs,'artists'=>$artists,'livres'=>$livres,
            'emplois'=>$emplois,'scolarites'=>$scolarite
        ));
    }

    public function autreAlbumAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        $photos=$em->getRepository(Album::class)->findBy(array('user' => $id),array('datePublication' => 'ASC'));
        //------------------------------
        if ($request->isMethod('POST')) {
            if ($request->request->has('idautreprofil')) {
                $user_signal = new Signaler();
                $user_signal->setCause(($request->get('ncontenusignal')));
                $user_signal->setIdUser(($request->get('idautreprofil')));
                $em->persist($user_signal);
                $em->flush();
                $idautreuser = ($request->get('idautreprofil'));
                $lastautreuser = $em->getRepository(User::class)->findBy(array('id' => $idautreuser));
                return $this->redirectToRoute("autre_profil_album", array('id' => $lastautreuser[0]->getId()));
            }
        }
        //-----------------------------------------------
        return $this->render('ProfilBundle:Default:autreAlbum.html.twig', array(
            'autreUser' => $u[0],'photos'=>$photos
        ));
    }
    //----------------------------------MOBILE SERVICES--------------
    public function getPublicationsAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        if (in_array("ROLE_SUPER_ADMIN", $u->getRoles())) {
            return $this->redirectToRoute("admin");
        }
        //------
        $em = $this->getDoctrine()->getManager();
        $pubs = $em->getRepository(Publication::class)->findBy(array('user' => $u->getId()),array('datePublication' => 'DESC'));
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($pubs, null, array('attributes' => array('id','datePublication','contenu','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //---------------------------------------------------------------
    public function getCentresAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        //------
        $em = $this->getDoctrine()->getManager();

        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u->getId(),'type' => 'livre')); 
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        //$data=$serializer->normalize($films, null, array('attributes' => array('id','type','contenu','user'=>['id','nom','prenom','image'])));
        //$data2=$serializer->normalize($series, null, array('attributes2' => array('id','type','contenu','user'=>['id','nom','prenom','image'])));
        //$data3=$serializer->normalize($artists, null, array('attributes2' => array('id','type','contenu','user'=>['id','nom','prenom','image'])));
        //$data4=$serializer->normalize($livres, null, array('attributes2' => array('id','type','contenu','user'=>['id','nom','prenom','image'])));
        $arrayData = $arrayName = array('films' => $films, 'series'=>$series,'livres'=>$livres,'artists'=>$artists);
        $data=$serializer->normalize($arrayData, null, array('attributes' => array('id','type','contenu','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //---------------------------------------------------------------
    public function getLoisirsAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        //------
        $em = $this->getDoctrine()->getManager();
        $loisirs = $em->getRepository(Loisir::class)->findBy(array('user' => $u->getId()));
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($loisirs, null, array('attributes' => array('id','contenu','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //---------------------------------------------------------------
    public function getEmploisAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        //------
        $em = $this->getDoctrine()->getManager();
        $emplois = $em->getRepository(Emploi::class)->findBy(array('user' => $u->getId()),array('dateDebut' => 'ASC'));
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($emplois, null, array('attributes' => array('id','contenu','dateDebut','dateFin','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //---------------------------------------------------------------
    public function getScolariteAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        //------
        $em = $this->getDoctrine()->getManager();
        $scolarite = $em->getRepository(Scolarite::class)->findBy(array('user' => $u->getId()),array('dateDebut' => 'ASC'));
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($scolarite, null, array('attributes' => array('id','contenu','dateDebut','dateFin','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //---------------------------------------------------------------
    public function ajouterPhotoAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $album = new Album();
        $album->setUrl($request->get('image'));
        $d = new \DateTime("now");
        $album->setDatePublication($d);
        $album->setUser($u);

        $em->persist($album);
        $em->flush();

        return new JsonResponse("OK");
    }
    //---------------------------------------------------------------
    public function getAlbumAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $photos=$em->getRepository(Album::class)->findBy(array('user' => $u->getId()),array('datePublication' => 'DESC'));

        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($photos, null, array('attributes' => array('id','url','datePublication','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //------------------------
    public function supprimerPhotoAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $album = $em->getRepository(Album::class)->find($request->get("idImg"));

        $em->remove($album);
        $em->flush();

        return new JsonResponse("OK");
    }
    //---------------------------
    public function modifierPublicationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Publication::class)->find($request->get("idpub"));
        $p->setContenu(($request->get('newContenu')));
        $d = new \DateTime("now");
        $p->setDatePublication($d);
        $em->persist($p);
        $em->flush();
        return new JsonResponse("OK");
    }
    //-----------------------------------
    public function supprimerPublicationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $p= $em->getRepository(Publication::class)->find($request->get("idpub"));
        $em->remove($p);
        $em->flush();
        return new JsonResponse("OK");
    }
    //---------------------------------------
    public function ajouterPublicationAction(Request $request)
    {
        $u= $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $p = new Publication();
        $p->setContenu(($request->get('contenu')));
        $d = new \DateTime("now");
        $p->setDatePublication($d);
        $p->setUser($u);
        $em->persist($p);
        $em->flush();
        return new JsonResponse("OK");
    }
    //--------------------------------------
    public function getSuggestionAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        //------
        $em = $this->getDoctrine()->getManager();
        $users_sug = $em->getRepository(User::class)->findSuggestionUsers("ROLE_SUPER_ADMIN",$u->getSalt(),$u->getId());
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($users_sug, null, array('attributes' => array('id','nom','prenom','dateNaissance','image')));
        return new JsonResponse($data);
    }
    //--------------------------------------
    public function getAutrePubsAction(Request $request)
    {
        $idu = $request->get('idautreuser');
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $idu));
        $pubs = $em->getRepository(Publication::class)->findBy(array('user' => $idu),array('datePublication' => 'DESC'));
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($pubs, null, array('attributes' => array('id','datePublication','contenu','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //--------------------------------------
    public function getAutreUserAction(Request $request)
    {
        $idu = $request->get('idautreuser');
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository(User::class)->findBy(array('id' => $idu));
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $normalizer->setIgnoredAttributes(array('interets','acceptors','requesters','sendedDemandes','receivedDemandes'));
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($u, null);
        return new JsonResponse($data);
    }
    //---------------------------------------------------------------
    public function getAutreCentresAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('idautreuser');
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        //------        
        $films = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u[0]->getId(),'type' => 'film'));
        $series = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u[0]->getId(),'type' => 'serie'));
        $artists = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u[0]->getId(),'type' => 'artist'));
        $livres = $em->getRepository(CentreInteret::class)->findBy(array('user' => $u[0]->getId(),'type' => 'livre'));        
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));        
        $arrayData = $arrayName = array('films' => $films, 'series'=>$series,'livres'=>$livres,'artists'=>$artists);
        $data=$serializer->normalize($arrayData, null, array('attributes' => array('id','type','contenu','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
        //---------------------------------------------------------------
    public function getAutreLoisirsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('idautreuser');
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        //------        
        $loisirs = $em->getRepository(Loisir::class)->findBy(array('user' => $u[0]->getId()));
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($loisirs, null, array('attributes' => array('id','contenu','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //---------------------------------------------------------------
    public function getAutreEmploisAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('idautreuser');
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        //------        
        $emplois = $em->getRepository(Emploi::class)->findBy(array('user' => $u[0]->getId()),array('dateDebut' => 'ASC'));
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($emplois, null, array('attributes' => array('id','contenu','dateDebut','dateFin','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //---------------------------------------------------------------
    public function getAutreScolariteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->get('idautreuser');
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        //------        
        $scolarite = $em->getRepository(Scolarite::class)->findBy(array('user' => $u[0]->getId()),array('dateDebut' => 'ASC'));
        //------------------------------
        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($scolarite, null, array('attributes' => array('id','contenu','dateDebut','dateFin','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //---------------------------------------------------------------
    public function getAutreAlbumAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('idautreuser');
        $u = $em->getRepository(User::class)->findBy(array('id' => $id));
        
        $photos=$em->getRepository(Album::class)->findBy(array('user' => $u[0]->getId()),array('datePublication' => 'DESC'));

        $normalizer = new ObjectNormalizer();
        $serializer=new Serializer(array(new DateTimeNormalizer(),$normalizer));
        $data=$serializer->normalize($photos, null, array('attributes' => array('id','url','datePublication','user'=>['id','nom','prenom','image'])));
        return new JsonResponse($data);
    }
    //--------------------------------------------
    public function signalerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('idautreuser');
        $contenu = $request->get('contenu');

        $user_signal = new Signaler();
        $user_signal->setCause($contenu);
        $user_signal->setIdUser($id);
        $em->persist($user_signal);
        $em->flush();

        return new JsonResponse("OK");
    }
    //---------------------------
    public function modifierUserPhotoAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $user = $u;
        $em = $this->getDoctrine()->getManager();
        $user->setImage($request->get('imageurl'));
        $em->persist($user);
        $em->flush();
        return new JsonResponse("OK");
    }
    //---------------------------
    public function modifierUserAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $user = $u;
        $em = $this->getDoctrine()->getManager();

        $user->setNom($request->get('nom'));
        $user->setPrenom($request->get('prenom'));
        $user->setGenre($request->get('sexe'));
        $d = new \DateTime($request->get('datenaiss'));
        $user->setDateNaissance($d);
        //$user->setPays($request->get('pays'));
        //$user->setVille($request->get('ville'));
        //$user->setRegion($request->get('region'));
        $user->setTel($request->get('tel'));
        $user->setPlaceNaiss($request->get('placenaiss'));
        $user->setRelegion($request->get('religion'));
        $user->setApropos($request->get('apropos'));
        $user->setFacebook($request->get('facebook'));
        $user->setTwitter($request->get('twitter'));
        $user->setInstagram($request->get('instagram'));
        $user->setOccupation($request->get('occ'));

        $em->persist($user);
        $em->flush();
        return new JsonResponse("OK");
    }
    //------------------------------
    public function modifierUserLocationAction(Request $request)
    {
        $u = $this->container->get('security.token_storage')->getToken()->getUser();
        $user = $u;
        $em = $this->getDoctrine()->getManager();
        $user->setSalt($request->get('location'));
        $em->persist($user);
        $em->flush();
        return new JsonResponse("OK");        
    }
}
