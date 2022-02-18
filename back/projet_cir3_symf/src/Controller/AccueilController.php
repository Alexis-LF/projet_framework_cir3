<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Espece;
use App\Entity\Echouage;
use App\Entity\Zone;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil_index")
     */
    public function index(EntityManagerInterface $em): Response
    {
        $especes = $em ->getRepository(Espece::class) ->findAll();
        $zones = $em ->getRepository(Zone::class) ->findAll();
        return $this->render('accueil/index.html.twig', [
            'zones' => $zones,
            'especes' => $especes,
            'zone_select' => [
                "id" => 0,
            ],
            'espece_select' => [
                "id" => 1,
            ],            
        ]);
    }

    /**
     * @Route("/recherche", name="accueil_recherche")
     */
    public function recherche(EntityManagerInterface $em): Response
    {
        // On vérifie l'URI
        $zone_id = $_GET["zone"];
        $espece_id = $_GET["espece"];
        if ( (!is_numeric($zone_id)) || (!is_numeric($espece_id)))
        {
            return $this->render('accueil/erreur.html.twig', [
                'erreur_description' => "Les identifiants dans l'URL pour la zone ou pour l'espece ne sont pas des nombres",
            ]);
        }

        $nb_zones = $em ->getRepository(Zone::Class) ->get_nb_zones();
        $espece = $em ->getRepository(Espece::class) ->get_nom($espece_id);
        $zone = "toutes zones";
        if ($zone_id != 0)
        {
            $zone = $em ->getRepository(Zone::class) ->get_nom($zone_id);
        }        

        // 1re requête
        // affichage du nb d'animaux d'une espèce particulière
        // par date et par zone
        $resultats = $em ->getRepository(Echouage::class) 
            ->get_echouages_espece($espece_id ,$zone_id);
        

        // 2nde requête
        // stats des zones
        $stats_par_zones = array();
        if ($zone_id == 0)
        {
            // toutes zones sélectionnées
            $i = 1;
           $derniere_zone = $nb_zones;
        }
        else
        {
            $i = $zone_id;
            $derniere_zone = $zone_id;
        }
        while($i <= $derniere_zone)
        {
            // récupération d'un tableau de nombres pour chaque zone
            $tab_vals = $em ->getRepository(Echouage::class) 
                ->get_nb_espece_dans_zone($espece_id, $i);
            array_push(
                $stats_par_zones, 
                // récupération des statistiques du tableau
                $em ->getRepository(Zone::class) 
                    ->tab_stats_zones($tab_vals, $i)
            );
            $i++;
        }

        $especes = $em ->getRepository(Espece::class) ->findAll();
        $zones = $em ->getRepository(Zone::class) ->findAll();
        return $this->render('accueil/recherche.html.twig', [
            'zones' => $zones,
            'especes' => $especes,
            'zone_select' => [
                "id" => $zone_id,
                "zone" => $zone
            ],
            'espece_select' => [
                "id" => $espece_id,
                "espece" => $espece
            ],
            'resultats' => $resultats,
            'stats_zones' => $stats_par_zones,
        ]);
    }
}
