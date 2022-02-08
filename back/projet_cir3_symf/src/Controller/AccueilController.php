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
        $zone_id = $_GET["zone"];
        $espece_id = $_GET["espece"];
        if ( (!is_numeric($zone_id)) || (!is_numeric($espece_id)))
        {
            return $this->render('accueil/erreur.html.twig', [
                'erreur_description' => "Les identifiants dans l'URL pour la zone ou pour l'espece ne sont pas des nombres",
            ]);
        }
        
        $requete = $entityManager -> createQueryBuilder("e")
        ->select('e.id' , 'e.date' , 'e.nombre', 'z.zone' , 's.espece')
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
        ->orderBy('e.date,z.zone', 'ASC')
        ->getQuery();

        $resultats = $requete->getResult();



        return $this->render('accueil/recherche.html.twig', [
            'zone_id' => $zone_id,
            'espece_id' => $espece_id,
            'resultats' => $resultats,
        ]);
    }
}
