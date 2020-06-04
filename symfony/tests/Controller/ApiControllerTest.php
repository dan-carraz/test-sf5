<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    private static KernelBrowser $client;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        self::$client = static::createClient();
    }

    public function setUp()
    {
        parent::setUp();
        self::bootKernel();

        /** @var EntityManager $entityManager */
        $entityManager = self::$kernel->getContainer()->get("doctrine.orm.entity_manager");
        $entityManager->createQueryBuilder()->delete(User::class)
            ->getQuery()
            ->execute();

        $stmt = $entityManager->getConnection();
        $stmt->prepare('TRUNCATE TABLE user')->execute();

        $user1 = new User();
        $user1->setFirstName("François");
        $user1->setLastName("Dupont");
        $entityManager->persist($user1);


        $user2 = new User();
        $user2->setFirstName("Marine");
        $user2->setLastName("Dupond");
        $entityManager->persist($user2);

        $entityManager->flush();

    }
}
