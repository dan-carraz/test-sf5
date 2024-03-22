<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Project;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ProjectFixtures extends Fixture implements FixtureGroupInterface
{
    private const PROJECTS = [
        'avis-vin' => ['name' => 'Avis Vin', 'host' => 'https://avis-vin.lefigaro.fr'],
        'figaro' => ['name' => 'Le Figaro', 'host' => 'https://www.lefigaro.fr'],
        'madame' => ['name' => 'Madame', 'host' => 'https://madame.lefigaro.fr'],
        'sante' => ['name' => 'Santé', 'host' => 'https://sante.lefigaro.fr'],
        'tvmag' => ['name' => 'TVMag', 'host' => 'https://tvmag.lefigaro.fr'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::PROJECTS as $projectTag => $project) {
            $projectEntity = new Project();
            $projectEntity->setName($project['name']);
            $projectEntity->setHost($project['host']);
            $manager->persist($projectEntity);
            $this->addReference('project_'.$projectTag, $projectEntity);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['redirection'];
    }
}
