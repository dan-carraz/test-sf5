<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    private ?QueryBuilder $addressQb = null;
    private ?array $result = null;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function getQueryBuilder(): QueryBuilder
    {
        if (null === $this->addressQb) {
            $this->addressQb = $this->createQueryBuilder('a')
                ->select(['a.street'])
                ->where('a.country = :country')
                ->setParameter('country', 'Ireland');
        }

        return $this->addressQb;
    }

    public function getResult(): array
    {
        if (null === $this->result) {
            $this->result = $this->getQueryBuilder()->getQuery()->getResult();
        }

        return $this->result;
    }
}
