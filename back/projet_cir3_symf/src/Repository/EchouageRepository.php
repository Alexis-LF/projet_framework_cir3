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
            ->select('e.date', 'z.zone', 'SUM(e.nombre) as nombre')
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
