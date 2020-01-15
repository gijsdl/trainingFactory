<?php

namespace App\Repository;

use App\Entity\Lesson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Cast\Int_;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    public function findByDate(\DateTime $date)
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select('l')
            ->where('l.date = :date')
            ->setParameter('date', $date)
            ->orderBy("l.time");


        return $qb->getQuery()->getResult();
    }

    public function findByBiggerDate(\DateTime $date, $userId)
    {
        $qb = $this->createQueryBuilder('l');
        $qb->select('l')
            ->where('l.date >= :date')
            ->andWhere('l.instructor_id = :id')
            ->setParameter('date', $date)
            ->setParameter("id", $userId)
            ->orderBy("l.date")
            ->orderBy('l.time');


        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Lesson[] Returns an array of Lesson objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lesson
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
