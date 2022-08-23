<?php

namespace App\GraphQL\Resolver;

use ApiPlatform\Core\GraphQl\Resolver\MutationResolverInterface;
use ApiPlatform\Core\GraphQl\Resolver\QueryItemResolverInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use GraphQL\Type\Definition\ResolveInfo;
use Symfony\Component\Uid\Ulid;

class UserResolver implements MutationResolverInterface, QueryItemResolverInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke($item, array $context)
    {
        /** @var ResolveInfo $info */
        $info = $context['info'];

        switch ($info->fieldName) {
            case 'retrieveByIdUser':
                return $this->retrieveByUserId($context);
            case 'updateByIdUser':
                return $this->updateByUserId($context);
            default:
                return null;
        }
    }

    private function updateByUserId(array $context): ?User
    {
        $userId = (new Ulid($context['args']['input']['id']))->toBinary();
        $qb = $this->userRepository->createQueryBuilder('user')
            ->update(User::class, 'user')
            ->andWhere('user.id = :id')
            ->setParameter('id', $userId);

        foreach ($context['args']['input'] as $field => $value) {
            if ('id' === $field) {
                continue;
            }
            $qb->set('user.'.$field, ':'.$field)
                ->setParameter($field, $value);
        }

        $qb->getQuery()->execute();

        return $this->userRepository->find($userId);
    }

    private function retrieveByUserId(array $context): ?User
    {
        return $this->userRepository->find((new Ulid($context['args']['id']))->toBinary());
    }
}
