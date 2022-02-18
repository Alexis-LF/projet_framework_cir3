<?php

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

    public function get_nb_zones()
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
