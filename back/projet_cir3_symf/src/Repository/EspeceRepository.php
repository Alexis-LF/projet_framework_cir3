<?php

namespace App\Repository;

use App\Entity\Espece;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Espece|null find($id, $lockMode = null, $lockVersion = null)
 * @method Espece|null findOneBy(array $criteria, array $orderBy = null)
 * @method Espece[]    findAll()
 * @method Espece[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EspeceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Espece::class);
    }

    public function get_nom($espece_id)
    {
        return $this-> find($espece_id) ->getEspece();
    }

    public function get_count()
    {
        return $this->createQueryBuilder('s')
            ->select('count(:c)')
            ->setParameter('c', "*")
            ->getQuery()
            ->getResult()[0][1];
    }
    
    public function get($espece_id = 0)
    {
        $especes = array();
        if ($espece_id == 0)
        {
            // toutes espèces sélectionnées
            $i = 1;
            $derniere_espece = $this ->get_count();
        }
        else
        {
            $i = $espece_id;
            $derniere_espece = $espece_id;
        }
        while($i <= $derniere_espece)
        {
            array_push(
                $especes,
                $this->createQueryBuilder('s')
                ->select('s.id','s.espece')
                ->where('s.id=:espece_id')   
                ->setParameter('espece_id', $i)
                ->getQuery()
                ->getResult()            
            );
            $i++;
        }
        return $especes;
    }     
    // /**
    //  * @return Espece[] Returns an array of Espece objects
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
    public function findOneBySomeField($value): ?Espece
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
