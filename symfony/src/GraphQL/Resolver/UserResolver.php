<?php

namespace App\GraphQL\Resolver;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class UserResolver implements ResolverInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getUser(ResolveInfo $info, int $id): User|null
    {
        return $this->prepareQuery($info)
            ->andWhere('users.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUsers(ResolveInfo $info): array
    {
        return $this->prepareQuery($info)
            ->getQuery()
            ->getResult();
    }

    private function prepareQuery(ResolveInfo $info): QueryBuilder
    {
        $qb = $this->userRepository->createQueryBuilder('users');
        if (isset($info->getFieldSelection()['addresses'])) {
            $qb->leftJoin('users.addresses', 'addresses')
                ->addSelect('addresses');
        }

        return $qb;
    }
}
