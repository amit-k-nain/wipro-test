<?php

namespace App\Repository;

use App\Entity\RouterDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
// use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @method RouterDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method RouterDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method RouterDetails[]    findAll()
 * @method RouterDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RouterDetailsRepository extends ServiceEntityRepository
{
    public $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, RouterDetails::class);
        $this->manager = $manager;
    }

    /**
     * @return RouterDetails[] Returns an array of RouterDetails objects
     */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function findOneBySomeField($value): ?RouterDetails
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function saveRouterDetails($sap_id, $host_name, $loopback, $mac_address)
    {
        $newRouterDetails = new RouterDetails();

        $newRouterDetails
            ->setSapid($sap_id)
            ->setHostname($host_name)
            ->setLoopback($loopback)
            ->setMacAddress($mac_address)
            ->setStatus(true);
            
        $this->manager->persist($newRouterDetails);
        $this->manager->flush();
        return $newRouterDetails->getId();
    }

}
