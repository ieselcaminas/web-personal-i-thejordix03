<?php

namespace App\Repository;

use App\Entity\Autor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Autor>
 *
 * @method Autor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autor[]    findAll()
 * @method Autor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Autor::class);
    }
    public function findAllWithMangaCount(): array
{
    return $this->createQueryBuilder('a')
        ->leftJoin('a.mangas', 'm')
        ->addSelect('COUNT(m) AS numMangas')
        ->groupBy('a.id')
        ->getQuery()
        ->getResult();
}
public function findByNombre(string $search): array
{
    return $this->createQueryBuilder('a')
        ->where('a.nombre LIKE :search')
        ->setParameter('search', '%' . $search . '%')
        ->orderBy('a.nombre', 'ASC')
        ->getQuery()
        ->getResult();
}



//    /**
//     * @return Autor[] Returns an array of Autor objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Autor
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
