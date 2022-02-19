<?php

namespace App\Repository;

use App\Entity\Echouage;
use App\Entity\Zone;
use App\Entity\Espece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Echouage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Echouage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Echouage[]    findAll()
 * @method Echouage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EchouageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Echouage::class);
    }

    public function get_echouages_espece($espece_id ,$zone_id = 0 )
    {
        $requete = $this->createQueryBuilder('e')
            ->select('e.date', 'z.zone','z.id AS zone_id' , 'SUM(e.nombre) AS nombre')
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
            ->groupBy('e.date,z.zone,zone_id')
            ->orderBy('e.date,z.zone', 'ASC')
            ->getQuery();

        return $requete->getResult();
    }

    public function get_nb_espece_dans_zone($espece_id ,$zone_id)
    {
        $requete = $this->createQueryBuilder('e')
            ->select('SUM(e.nombre) as nombre')
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

    public function tab_dates($espece_id, $order_by)
    {
        $resultat = $this->createQueryBuilder('e')
        ->select('e.date')
        ->leftJoin(
            Espece::class,
            's',
            \Doctrine\ORM\Query\Expr\Join::WITH,
            'e.espece = s.id'
        )
        ->where('s.id=:espece_id')   
        ->setParameter('espece_id', $espece_id)
        ->orderBy('e.date', $order_by)
        ->groupBy('e.date')
        ->getQuery()
        ->getResult();
        
        if (count($resultat) == 0)
        {
            return array
            (
                [
                    "date" => 0,
                ]
            );
        }
        return $resultat;
    }    

    public function date_min($espece_id)
    {
        return $this->tab_dates($espece_id, "ASC")[0]["date"];
    }

    public function date_max($espece_id)
    {
        return $this->tab_dates($espece_id, "DESC")[0]["date"];  
    }    
    
    public function get_tab_date_zone($espece_id,$zone_id)
    {
        $tab = array();
        $min = $this ->date_min($espece_id);
        $max = $this ->date_max($espece_id);
        for ($i=$min; $i <= $max; $i++) 
        { 
            $nb_echouages = (int)$this->createQueryBuilder('e')
                ->select('SUM(e.nombre) AS nombre')
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
                ->andWhere('e.date=:date')   
                ->setParameter('date', $i)            
                ->getQuery()
                ->getResult()[0]["nombre"];
            array_push(
                $tab,
                [
                    "date" => $i,
                    "nombre" => $nb_echouages
                ]
            );
        }
        return $tab;
    }

    public function get_nb_date_zone($espece_id,$zone_id,$date)
    {
        return (int)$this->createQueryBuilder('e')
            ->select('SUM(e.nombre) AS nombre')
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
            ->andWhere('e.date=:date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()[0]["nombre"];
    }





    // /**
    //  * @return Echouage[] Returns an array of Echouage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Echouage
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
