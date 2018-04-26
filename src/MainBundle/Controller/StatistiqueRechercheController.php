<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StatistiqueRechercheController extends Controller
{
    public function generaleAction(Request $request)
    {
        $country = $request->get("country");
        if($country == null) $country = "tn";
        $manager = $this->getDoctrine()->getManager();
        $all = $manager->getRepository("MainBundle:Recherche")->countAll();
        $allByYear = $manager->getRepository("MainBundle:Recherche")->countAllByYear();
        $allByMonth = $manager->getRepository("MainBundle:Recherche")->countAllByMonth();
        $allByToday = $manager->getRepository("MainBundle:Recherche")->countAllByToday();
        $statByCountry = $manager->getRepository("MainBundle:Recherche")->statByCountry();
        $statByTunis = $manager->getRepository("MainBundle:Recherche")->statByStat($country);
        return $this->render("@Main/Default/generalStatistiqueRecherche.html.twig",array(
            "all"=>$all,"allByYear"=>$allByYear,"allByMonth" => $allByMonth,"allByToday"=>$allByToday,"statByCountry"=>$statByCountry,"statByTunis"=>$statByTunis
        ));
    }

    public function rechercheListAction()
    {
        $manager = $this->getDoctrine()->getManager();
        $data = $manager->getRepository("MainBundle:Recherche")->rechercheListe();
        return $this->render("@Main/Default/listeRecherches.html.twig",array(
           "liste"=>$data
        ));
    }

}
