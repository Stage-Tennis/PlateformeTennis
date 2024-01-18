<?php

namespace App\Repository;

use App\Entity\CourseType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseType>
 *
 * @method CourseType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourseType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourseType[]    findAll()
 * @method CourseType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseType::class);
    }

//    /**
//     * @return CourseType[] Returns an array of CourseType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CourseType
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}