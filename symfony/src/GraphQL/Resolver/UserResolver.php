<?php

namespace App\GraphQL\Resolver;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use GraphQL\Type\Definition\ResolveInfo;
use JetBrains\PhpStorm\Pure;

class UserResolver extends AbstractResolver
{
    protected const ENTITY_CLASS = User::class;
    protected const ENTITY_ALIAS = "users";

    #[Pure] public function __construct(UserRepository $userRepository)
    {
        parent::__construct($userRepository);
    }

    /**
     * @param ResolveInfo $info
     * @param int $id
     * @return array|null
     * @throws NonUniqueResultException
     */
    public function getUser(ResolveInfo $info, int $id): array|null
    {
        return $this->prepareQuery($info)
            ->andWhere(static::ENTITY_ALIAS . ".id = :id")
            ->setParameter("id", $id)
            ->getQuery()->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);
    }

    public function getUsers(ResolveInfo $info): array
    {
        return $this->prepareQuery($info)
            ->getQuery()->getArrayResult();
    }
}
