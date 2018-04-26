<?php

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use APY\DataGridBundle\Grid\Action\DeleteMassAction;
use APY\DataGridBundle\Grid\Action\MassAction;
use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\ActionsColumn;
use APY\DataGridBundle\Grid\Column\BlankColumn;
use APY\DataGridBundle\Grid\Export\ExcelExport;
use APY\DataGridBundle\Grid\Source\Entity;

class ProfilAdminController extends Controller
{
    public function indexAction()
    {
        $admin = $this->container->get('security.token_storage')->getToken()->getUser();

        return $this->render('MainBundle:ProfilAdmin:indexAdmin.html.twig', array(
            'curr_user' => $admin
        ));
    }

    public function utilisateursAction()
    {
        /*$admin = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();

        return $this->render('MainBundle:ProfilAdmin:utilisateursAdmin.html.twig', array(
            'curr_user' => $admin,'users'=>$users
        ));*/

        // Creates a simple grid based on your entity (ORM)
        $source = new Entity('MainBundle:User');

        // Get a Grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);

        // Set the selector of the number of items per page
        $grid->setLimits(array(10, 10, 15));

        // Set the default page
        $grid->setDefaultPage(1);

        // Add a mass action with static callback
        /*$yourMassAction = new MassAction('Action 1', 'TestBundle:Default:myStaticMethod');
        $var1 = "";
        $var2 = "";
        $yourMassAction->setParameters(array('param1' => $var1, 'param2' => $var2));
        $grid->addMassAction($yourMassAction);*/

        // Add a delete mass action
        //$grid->addMassAction(new DeleteMassAction());

        // Add a column in the 5 position
        /*$MyColumn = new BlankColumn(array('id' => 'My Column', 'title'=>'AAAA', 'size' => '54'));
        $grid->addColumn($MyColumn, 5);*/

        // Add row actions in the default row actions column
        /*$myRowAction = new RowAction('Edit', 'indexAdmin');
        $grid->addRowAction($myRowAction);

        $myRowAction = new RowAction('Delete', 'indexAdmin', true, '_self');
        $grid->addRowAction($myRowAction);*/

        // Custom actions column in the wanted position
        /*$myActionsColumn = new ActionsColumn('info_column','Info');
        $grid->addColumn($myActionsColumn, 6);*/

        /*$myRowAction = new RowAction('Show', 'indexAdmin');
        $myRowAction->setColumn('info_column');
        $grid->addRowAction($myRowAction);*/

        //Hide column
        $grid->hideColumns(array('id','usernameCanonical','emailCanonical','enabled','password','confirmationToken','passwordRequestedAt','roles','updatedAt','ville','image','apropos','place_naiss'));

        //set Column Order
        $userColumns = array('username','email','lastLogin','salt','nom', 'prenom','date_naissance','tel','genre','occupation','pays','region','relegion');
        $grid->setColumnsOrder($userColumns);

        //Export to Excel
        $grid->addExport(new ExcelExport('Excel Export', 'ListUtilisateurs'));

        //Rename columns
        $column = $grid->getColumn('username');
        $column->setTitle("Username");
        $column = $grid->getColumn('email');
        $column->setTitle("E-mail");
        $column = $grid->getColumn('lastLogin');
        $column->setTitle("Dernière Connexion");
        $column = $grid->getColumn('salt');
        $column->setTitle("Place de Connexion");
        $column = $grid->getColumn('nom');
        $column->setTitle("Nom");
        $column = $grid->getColumn('prenom');
        $column->setTitle("Prénom");
        $column = $grid->getColumn('date_naissance');
        $column->setTitle("Date de Naissance");
        $column = $grid->getColumn('tel');
        $column->setTitle("Numéro de Téléphone");
        $column = $grid->getColumn('genre');
        $column->setTitle("Sexe");
        $column = $grid->getColumn('occupation');
        $column->setTitle("Occupation");
        $column = $grid->getColumn('pays');
        $column->setTitle("Pays");
        $column = $grid->getColumn('region');
        $column->setTitle("Ville");
        $column = $grid->getColumn('relegion');
        $column->setTitle("Religion");



        //Set Empty Grid Message
        $grid->setNoResultMessage('Pas de Resultat !');

        $param1 = "";

        // Return the response of the grid to the template
        return $grid->getGridResponse('MainBundle:ProfilAdmin:utilisateursAdmin.html.twig', array('param1' => $param1));
        //return $this->render('MainBundle:ProfilAdmin:utilisateursAdmin.html.twig', array('grid' => $grid));
        //return $grid->getGridResponse('MainBundle:ProfilAdmin:utilisateursAdmin.html.twig');
    }

    public function utilisateurSignalAction()
    {
        $admin = $this->container->get('security.token_storage')->getToken()->getUser();

        return $this->render('MainBundle:ProfilAdmin:utilSignal.html.twig', array(
            'curr_user' => $admin
        ));
    }

    public function statProfilAction()
    {
        $admin = $this->container->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $nb_users = $em->getRepository(User::class)->findByRole("ROLE_SUPER_ADMIN");
        //---------
        $nb_users_actif = $em->getRepository(User::class)->findActif("ROLE_SUPER_ADMIN");
        $nb_users_inactif = $em->getRepository(User::class)->findInactif("ROLE_SUPER_ADMIN");
        //-----------
        $pourcentage_actif = ($nb_users_actif*100)/$nb_users;
        $pourcentage_inactif = ($nb_users_inactif*100)/$nb_users;
        //--------------
        $nb_fb = $em->getRepository(User::class)->findByFacebook("ROLE_SUPER_ADMIN");
        $nb_twi = $em->getRepository(User::class)->findByTwitter("ROLE_SUPER_ADMIN");
        $nb_inst = $em->getRepository(User::class)->findByInstagram("ROLE_SUPER_ADMIN");

        return $this->render('MainBundle:ProfilAdmin:statistiqueAdmin.html.twig', array(
            'curr_user' => $admin,'nb_users'=>$nb_users,'actif'=>$pourcentage_actif,'inactif'=>$pourcentage_inactif,
            'nb_actif'=>$nb_users_actif,'nb_inactif'=>$nb_users_inactif,'nb_fb'=>$nb_fb,'nb_twi'=>$nb_twi,
            'nb_inst'=>$nb_inst
        ));
    }

    public function myGridAction()
    {
        // Creates a simple grid based on your entity (ORM)
        $source = new Entity('MainBundle:User');

        // Get a Grid instance
        $grid = $this->get('grid');

        // Attach the source to the grid
        $grid->setSource($source);


        // Return the response of the grid to the template
        //return $grid->getGridResponse('MainBundle:ProfilAdmin:statistiqueAdmin.html.twig', array('param1' => $param1));
        //return $this->render('TestBundle:Default:myGrid.html.twig', array('grid' => $grid));
        return $grid->getGridResponse('MainBundle:ProfilAdmin:utilisateursAdmin.html.twig');
    }

    function myStaticMethodAction($primaryKeys, $allPrimaryKeys, $param1, $param2)
    {
        //return new Response();
        return $this->render('MainBundle:ProfilAdmin:index.html.twig');
    }

}
