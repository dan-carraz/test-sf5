<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Doctrine\ORM\QueryBuilder;

interface AddStuffToDoctrineInterface
{
    public function addQueryParams(): void;

    public function getQb(): QueryBuilder;

    public function setQb(QueryBuilder $qb): void;

    public function setResult(array $result): void;

    public function getResult(): array;
}
