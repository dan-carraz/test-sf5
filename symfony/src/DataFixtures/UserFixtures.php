<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use App\Factory\AddressFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $UserPosts = UserFactory::createMany(10);

        foreach ($UserPosts as $userPost) {
            /** @var User $user */
            $user = $userPost->object();

            $addressPosts = AddressFactory::createMany(mt_rand(0, 3));

            foreach ($addressPosts as $addressPost) {
                /** @var Address $address */
                $address = $addressPost->object();
                $user->addAddress($address);
            }
        }

        $manager->flush();
    }
}
