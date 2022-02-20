<?php
/**
 * Méthodes de repository (requêtes SQL) pour Zone
 *
 * @package    Projet Framework
 * @author     Alexis Le Floch <alexis.le-floch@isen-ouest.yncrea.fr> , Noam Nedelec-Salmon <noam.nedelec-salmon@isen-ouest.yncrea.fr>
 * @version    1.0 
 */

namespace App\Repository;

use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Zone|null find($id, $lockMode = null, $lockVersion = null)
 * @method Zone|null findOneBy(array $criteria, array $orderBy = null)
 * @method Zone[]    findAll()
 * @method Zone[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    public function get_count()
    {
        return $this->createQueryBuilder('z')
            ->select('count(:c)')
            ->setParameter('c', "*")
            ->getQuery()
            ->getResult()[0][1];
    }

    public function get_nom($zone_id)
    {
        return $this-> find($zone_id) ->getZone();
    }

    public function tab_stats_zones($tab_vals, $zone_id)
    {
        return [
            "zone" => $this ->get_nom($zone_id),
            "min" => min($tab_vals),
            "max" => max($tab_vals),
            "avg" => array_sum($tab_vals)/count($tab_vals)
        ];        
    }

    public function get($zone_id = 0)
    {
        $zones = array();
        if ($zone_id == 0)
        {
            // toutes zones sélectionnées
            $i = 1;
            $derniere_zone = $this ->get_count();
        }
        else
        {
            $i = $zone_id;
            $derniere_zone = $zone_id;
        }
        while($i <= $derniere_zone)
        {
            array_push(
                $zones,
                $this->createQueryBuilder('z')
                ->select('z.id','z.zone')
                ->where('z.id=:zone_id')   
                ->setParameter('zone_id', $i)
                ->getQuery()
                ->getResult()            
            );
            $i++;
        }
        return $zones;
    }    
   
    // /**
    //  * @return Zone[] Returns an array of Zone objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Zone
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
