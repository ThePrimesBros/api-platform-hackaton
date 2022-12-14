<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $pwd = "$2y$13$1LJzqnNyoUOY29DUzfigVu9.V09oruc3ynZX9413wNmlTGErr3I1G";
        $user = new User();
        $user->setEmail('user@user.fr');
        $user->setPassword($pwd);
        $user->setRoles(['ROLE_USER']);

        $admin = new User();
        $admin->setEmail('admin@user.fr');
        $admin->setPassword($pwd);
        $admin->setRoles(['ROLE_ADMIN']);

        $customer = new User();
        $customer->setEmail('customer@user.fr');
        $customer->setPassword($pwd);
        $customer->setRoles(['ROLE_CUSTOMER']);

        $coach = new User();
        $coach->setEmail('coach@user.fr');
        $coach->setPassword($pwd);
        $coach->setRoles(['ROLE_COACH']);

        $manager->persist($user);
        $manager->persist($admin);
        $manager->persist($customer);
        $manager->persist($coach);


        // generate 100 users
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail($faker->email);
            $user->setPassword($pwd);
            $user->setRoles(['ROLE_USER']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
