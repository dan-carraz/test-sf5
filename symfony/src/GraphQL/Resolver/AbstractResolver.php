<?php


namespace App\GraphQL\Resolver;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectRepository;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphiQLBundle\Config\GraphQLEndpoint\GraphQLEndpointInvalidSchemaException;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

abstract class AbstractResolver implements ResolverInterface
{
    protected const ENTITY_CLASS = "";
    protected const ENTITY_ALIAS = "";
    /**
     * @var ObjectRepository
     */
    protected ObjectRepository $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function prepareFields(array $fieldSelection): array
    {
        $fields = [];
        foreach ($fieldSelection as $fieldName => $active) {
            if (!$active) {
                continue;
            }
            if (!property_exists(static::ENTITY_CLASS, $fieldName)) {
                throw new GraphQLEndpointInvalidSchemaException($fieldName . " is not a valid field name");
            }

            $fields[] = static::ENTITY_ALIAS . "." . $fieldName;
        }

        return $fields;
    }


    protected function prepareQuery(ResolveInfo $info): QueryBuilder
    {
        return $this->repository
            ->createQueryBuilder(static::ENTITY_ALIAS)
            ->select($this->prepareFields($info->getFieldSelection()));
    }
}
