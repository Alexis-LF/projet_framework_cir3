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
    public function index(EntityManagerInterface $entityManager): Response
    {
        $especes = $entityManager
            ->getRepository(Espece::class)
            ->findAll();
        $zones = $entityManager
            ->getRepository(Zone::class)
            ->findAll();
        return $this->render('accueil/index.html.twig', [
            'controller_name' => 'AccueilController',
            'zones' => $zones,
            'especes' => $especes,
        ]);
    }

    /**
     * @Route("/recherche", name="accueil_recherche")
     */
    public function recherche(EntityManagerInterface $entityManager): Response
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
        $nb_zones = $entityManager -> createQueryBuilder("z")
        ->select('count(:c)')
        ->from(Zone::class,"z")
        ->setParameter('c', "*")
        ->getQuery()
        ->getResult()[0][1];


        // Récupération des noms des zones / espèces
        $espece = $entityManager
        ->getRepository(Espece::class)
        ->find($espece_id)
        ->getEspece();
        $zone = "toutes zones";
        if ($zone_id != 0)
        {
            $zone = $entityManager
            ->getRepository(Zone::class)
            ->find($zone_id)
            ->getZone();
        }

        

        // création de la requête
        // affichage du nb d'animaux d'une espèce particulière
        // par date et par zone
        $requete = $entityManager -> createQueryBuilder("e")
        ->select('e.date', 'z.zone', 'SUM(e.nombre) as nombre')
        ->from(Echouage::class, 'e')
        ->leftJoin(
            Zone::class,
            'z',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'e.zone = z.id'
        )
        ->leftJoin(
            Espece::class,
            's',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'e.espece = s.id'
        )
        ->where('s.id=:espece_id')   
        ->setParameter('espece_id', $espece_id);
        if ($zone_id != 0)
        {
            $requete = $requete 
            ->andWhere('z.id=:zone_id')   
            ->setParameter('zone_id', $zone_id);
        }
        $requete = $requete
        ->groupBy('e.date,z.zone')
        ->orderBy('e.date,z.zone', 'ASC')
        ->getQuery();
        $resultats = $requete->getResult();

        // 2nde requête
        // récupération d'un tableau de nombres pour chaque zone
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
            array_push(
                $stats_par_zones, 
                $this -> tab_stats_zones($entityManager, $espece_id, $i)
            );
            $i++;
        }   

        return $this->render('accueil/recherche.html.twig', [
            'zone' => [
                "id" => $zone_id,
                "zone" => $zone
            ],
            'espece' => [
                "id" => $espece_id,
                "espece" => $espece
            ],
            'resultats' => $resultats,
            'stats_zones' => $stats_par_zones,
        ]);
    }

    public function tab_stats_zones($entityManager, $espece_id, $zone_id)
    {
    
        $tab_vals = $this -> liste_nb_dans_zone($entityManager, $espece_id, $zone_id);
        return [
            "zone" => $entityManager
                ->getRepository(Zone::class)
                ->find($zone_id)
                ->getZone(),
            "min" => min($tab_vals),
            "max" => max($tab_vals),
            "avg" => array_sum($tab_vals)/count($tab_vals)
        ];
    }

    public function liste_nb_dans_zone($entityManager, $espece_id, $zone_id)
    {
        $requete = $entityManager -> createQueryBuilder("e")
        ->select('SUM(e.nombre) as nombre')
        ->from(Echouage::class, 'e')
        ->leftJoin(
            Zone::class,
            'z',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'e.zone = z.id'
        )
        ->leftJoin(
            Espece::class,
            's',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'e.espece = s.id'
        )
        ->where('s.id=:espece_id')   
        ->setParameter('espece_id', $espece_id)
        ->andWhere('z.id=:zone_id')   
        ->setParameter('zone_id', $zone_id)
        ->groupBy('e.date,z.zone')
        ->orderBy('nombre', 'ASC')
        ->getQuery();

        $resultats_sql = $requete->getResult();
        $resultats = array();
        foreach ($resultats_sql as $value) {
            array_push($resultats,(int)$value["nombre"]);
        }
        if (count($resultats) == 0)
        {
            array_push($resultats,0);
        }
        return $resultats;
    }
}
