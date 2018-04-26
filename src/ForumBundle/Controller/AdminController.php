<?php
/**
 * Created by PhpStorm.
 * User: ASUS
 * Date: 25/02/2018
 * Time: 20:36
 */

namespace ForumBundle\Controller;


use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\Histogram;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use MainBundle\Entity\Categorie;
use MainBundle\Entity\Post;
use MainBundle\MainBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AdminController extends Controller
{

    public function ForumAction(Request $request)

    {

        $marque = $request->get('marque');
        $em = $this->getDoctrine();
        $voitures = $em->getRepository("MainBundle:Categorie")->rechercheMarque($marque);

        return $this->render('ForumBundle:Forum/Admin:read_categorie.html.twig',array('voitures'=>$voitures));
    }

    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $modele= $em->getRepository(Categorie::class)->find($id);
        $em->remove($modele);
        $em->flush();
        return $this->redirectToRoute("forum_admin");
    }

    public function updateAction(Request $request, $id)
    {
        $em= $this->getDoctrine()->getManager();
        $voiture=$em->getRepository("MainBundle:Categorie")->find($id);
        $form=$this->createFormBuilder($voiture)
            ->add('libelle',TextType::class)
            ->add('description',TextType::class)
            ->add('date_ajout',DateType::class)
            ->add('image',FileType::class, array('label' => 'inserez une image','data_class' => null))
            ->add('save',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $file = $voiture->getImage();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('image_directory',$fileName),
                $fileName
            );

            $voiture->setImage($fileName);


            $em->flush();
            return $this->redirectToRoute("forum_admin");
        }
        return $this->render('ForumBundle:forum/Admin:update_categorie.html.twig', array(
            "form"=>$form->createView()

        ));
    }
    public function createAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $modele= new Categorie();
        $form=$this->createFormBuilder($modele)
            ->add('libelle',TextType::class)
            ->add('description',TextType::class)
            ->add('date_ajout',DateTimeType::class)
            ->add('image',FileType::class, array('label' => 'inserez une image','data_class' => null))
            ->add('save',SubmitType::class)
            ->getForm();
        $form->handleRequest($request);
        if (($form->isSubmitted()))
        {
            $file = $modele->getImage();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // moves the file to the directory where brochures are stored
            $file->move(
                $this->getParameter('image_directory',$fileName),
                $fileName
            );

            $modele->setImage($fileName);



            $modele= $form->getData();

            $modele->setDateAjout(new \DateTime('now '));
            $modele=$form->getData();
            $modele->setNombrePost('0');

            $em->persist($modele);
            $em->flush();
            return $this->redirectToRoute('forum_admin');
        }

        return $this->render('ForumBundle:forum/Admin:create_categorie.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    public function read_postAction(Request $request,$id)

    {
        $marque = $request->get('marque');
        $em = $this->getDoctrine();
        $voitures = $em->getRepository("MainBundle:Post")->recherchePost($marque,$id);
        return $this->render('ForumBundle:Forum/Admin:read_post.html.twig',array('voitures'=>$voitures,'id'=>$id));
    }

    public function delete_postAction($ids ,$id )
    {
        $em = $this->getDoctrine()->getManager();
        $modele= $em->getRepository(Post::class)->find($ids);
        $em->getRepository(Categorie::class)->findthis($id);
        $em->remove($modele);
        $em->flush();
        return $this->redirectToRoute("Admin_post_read",array('id'=>$id));
    }

    public function statAction()
    {
        $em = $this->getDoctrine()->getManager();
        $articles=$em->getRepository('MainBundle:Categorie')->findAll();
        $titles= array();
        $views= array();
        foreach ($articles as $article)
        {
            array_push($titles,$article->getLibelle());
            array_push($views,$article->getNombrePost());
        }
        dump($views);
        dump($titles);
        $bar = new BarChart();
        $bar->getData()->setArrayToDataTable([$titles,$views]);
        $bar->getOptions()->setTitle('Statistique sur les Categorie');
        $bar->getOptions()->getHAxis()->setTitle('Nombre des Sujet');
        $bar->getOptions()->getHAxis()->setMinValue(0);
        $bar->getOptions()->getVAxis()->setTitle('Categorie');
        $bar->getOptions()->setWidth(900);
        $bar->getOptions()->setHeight(500);

        $bar = new BarChart();
        $bar->getData()->setArrayToDataTable([$titles,$views]);
        $bar->getOptions()->setTitle('Statistique sur les Categorie');
        $bar->getOptions()->getHAxis()->setTitle('Nombre des Sujet');
        $bar->getOptions()->getHAxis()->setMinValue(0);
        $bar->getOptions()->getVAxis()->setTitle('Categorie');
        $bar->getOptions()->setWidth(900);
        $bar->getOptions()->setHeight(500);
        return $this->render('ForumBundle:Forum/Admin:stat.html.twig',array('barchart' => $bar));
    }


    /**
     * @return string
     */
    private function generateUniqueFileName()
    {

        return md5(uniqid());
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Post::class,
        ));

    }

}