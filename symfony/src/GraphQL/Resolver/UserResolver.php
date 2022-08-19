<?php

namespace App\GraphQL\Resolver;

use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use GraphQL\Type\Definition\ResolveInfo;
use Symfony\Component\Uid\Ulid;

class UserResolver implements QueryItemResolverInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke($item, array $context)
    {
        /** @var ResolveInfo $info */
        $info = $context['info'];

        if ('retrieveByIdUser' === $info->fieldName) {
            return $this->retrieveByUserId($context);
        }

        return $item;
    }

    private function retrieveByUserId(array $context): ?User
    {
        $qb = $this->userRepository->createQueryBuilder('user')
            ->andWhere('user.id = :id')
            ->setParameter('id', (new Ulid($context['args']['id']))->toBinary());

        $info = $context['info'];

        if (isset($info->getFieldSelection()['addresses'])) {
            $qb->leftJoin('user.addresses', 'addresses')
                ->addSelect('addresses');
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}
