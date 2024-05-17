<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\RedirectionFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class RedirectionFixtures extends Fixture implements FixtureGroupInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        //        RedirectionFactory::createMany(500, [
        //            'project' => $this->getReference('project_figaro'),
        //        ]);
        //
        //        RedirectionFactory::createMany(10, [
        //            'project' => $this->getReference('project_madame'),
        //        ]);
        //
        //        RedirectionFactory::createMany(400, [
        //            'project' => $this->getReference('project_sante'),
        //        ]);
    }

    public static function getGroups(): array
    {
        return ['redirection'];
    }

    public function getDependencies(): array
    {
        return [ProjectFixtures::class];
    }
}
