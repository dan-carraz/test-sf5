<?php

declare(strict_types=1);

namespace App\Twig\Components;

use Doctrine\ORM\QueryBuilder;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent]
class SecondComponent implements AddStuffToDoctrineInterface
{
    private array $result;

    private QueryBuilder $qb;

    public function getQb(): QueryBuilder
    {
        return $this->qb;
    }

    public function setQb(QueryBuilder $qb): void
    {
        $this->qb = $qb;
    }

    public function addQueryParams(): void
    {
        $this->qb->addSelect('a.city');
    }

    public function getResult(): array
    {
        return $this->result;
    }

    public function setResult(array $result): void
    {
        $this->result = $result;
    }
}
