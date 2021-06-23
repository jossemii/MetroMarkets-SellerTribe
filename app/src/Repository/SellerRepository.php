<?php

namespace App\Repository;

use App\Entity\Seller;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


/**
 * @method Seller|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seller|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seller[]    findAll()
 * @method Seller[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SellerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Seller::class);
        $this->manager = $manager;
    }

    public function saveSeller($data): int
    {

        # check if the request has a name.
        if (empty($data['name'])){
            throw new NotFoundHttpException("Expecting mandatory parameters!");
        }

        //create new seller obj.
        $seller = new Seller();
        $seller->setName($data["name"]);
        $seller->setCountry($data['country'] ?? null);
        $seller->setPostalCode($data['postal_code'] ?? null);

        //save seller to database
        $this->manager->persist($seller);
        $this->manager->flush();

        return $seller->getId();
    }

    // /**
    //  * @return Seller[] Returns an array of Seller objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Seller
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
