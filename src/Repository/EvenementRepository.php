<?php

namespace App\Repository;

use DateTime;
use DateTimeZone;
use App\Entity\Evenement;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Evenement>
 *
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    public function add(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Evenement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function findEvenementsAvenir()
    {
        // on va comparer la date fin de l'evenement avec la date d'ahujoud'hui 
        $now = new DateTime('Europe/paris');
        // on fait createquerybuilder
        return $this->createQueryBuilder('e')
        // on fait une condition 
        ->andWhere('e.date_debut >= :val')
        // on met les paramettre
        ->setParameter('val',$now)
        ->orderBy('e.date_debut','ASC')
        ->getQuery()
        ->getResult() ;
    }

    public function findEvenements()
    {
        // on va comparer la date fin de l'evenement avec la date d'ahujoud'hui 
        $now = new DateTime('Europe/paris');
        $statut ='validé';
        // on fait createquerybuilder
        return $this->createQueryBuilder('e')
        // on fait une condition 
        ->andWhere('e.date_debut > :val')
        ->andWhere('e.statut =:statut')
        // on met les paramettre
        ->setParameter('val',$now)
        ->setParameter('statut',$statut)
        ->orderBy('e.date_debut','ASC')
        ->getQuery()
        ->getResult() ;
    }

 
    public function findEvenementsPassees()
    {
        $now = new DateTime('Europe/paris');
        
        return $this->createQueryBuilder('e')
            ->andWhere('e.date_fin < :date_now')
            ->setParameter('date_now', $now)
            ->orderBy('e.date_debut', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }

    public function findEvenementsEncoursParCategorie($categorieId)
    {
        $now = new DateTime('Europe/paris');
       
        return $this->createQueryBuilder('e')
            ->innerJoin('e.categorie', 'c')
            ->andWhere('c.id = :categorie_id')
            ->andWhere('e.date_debut < :date_now and e.date_fin > :date_now')
            ->setParameter('categorie_id', $categorieId)
            ->setParameter('date_now', $now)
            ->orderBy('e.date_debut', 'ASC')
            ->getQuery()
            ->getResult();
    }

 
    


    

//    /**
//     * @return Evenement[] Returns an array of Evenement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Evenement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
