<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Service;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;
use Symfony\Component\Validator\Constraints\Length;

class AppFixtures extends Fixture
{
    private $encoder;

    public function  __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        // USER ADMIN (unique)
        $admin = new User();
        $admin
            ->setEmail('a@a.fr')
            ->setFirstName('Claire')
            ->setLastName('Saurel')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(
                $this->encoder->hashPassword($admin, 'Password1*')
            )
            ->setPhone('06.72.69.98.95')
            ->setZipCode($faker->postcode())
            ->setCity('Dijon')
            ->setAddress($faker->streetAddress());

        $manager->persist($admin);

        // USER CLIENT pour les tests avec les projets
        $client = new User();
        $client
            ->setEmail('varraut.corentin@gmail.com')
            ->setFirstName('Corentin')
            ->setLastName('Varraut')
            ->setRoles(["ROLE_CLIENT"])
            ->setPassword(
                $this->encoder->hashPassword($admin, 'Password1*')
            )
            ->setPhone('07.83.71.33.58')
            ->setZipCode($faker->postcode())
            ->setCity('Dijon')
            ->setAddress($faker->streetAddress());

        $manager->persist($client);


        // USER CONSULTANT pour les tests avec les services
        $consultant = new User();
        $consultant
            ->setEmail('consultant@a.fr')
            ->setFirstName('consultant')
            ->setLastName('consultant')
            ->setRoles(["ROLE_CONSULTANT"])
            ->setPassword(
                $this->encoder->hashPassword($admin, 'Password1*')
            )
            ->setPhone('0000000000')
            ->setZipCode($faker->postcode())
            ->setCity('Dijon')
            ->setAddress($faker->streetAddress());

        $manager->persist($consultant);

        // USER MANAGER
        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            $user
                ->setEmail($faker->email())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setRoles(["ROLE_MANAGER"])
                ->setPassword(
                    $this->encoder->hashPassword($user, 'Password1*')
                )
                ->setPhone('0000000000')
                ->setZipCode($faker->postcode())
                ->setCity('Dijon')
                ->setAddress($faker->streetAddress());

            $manager->persist($user);
        }

        // USER CLIENT
        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            $user
                ->setEmail($faker->email())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setRoles(["ROLE_CLIENT"])
                ->setPassword(
                    $this->encoder->hashPassword($user, 'Password1*')
                )
                ->setPhone('0000000000')
                ->setZipCode($faker->postcode())
                ->setCity('Dijon')
                ->setAddress($faker->streetAddress());

            $manager->persist($user);
        }

        // USER CONSULTANT
        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            $user
                ->setEmail($faker->email())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setRoles(["ROLE_CONSULTANT"])
                ->setPassword(
                    $this->encoder->hashPassword($user, 'Password1*')
                )
                ->setPhone('0000000000')
                ->setZipCode($faker->postcode())
                ->setCity('Dijon')
                ->setAddress($faker->streetAddress());

            $manager->persist($user);
        }

        // USER (de base)
        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            $user
                ->setEmail($faker->email())
                ->setFirstName($faker->firstName())
                ->setLastName($faker->lastName())
                ->setRoles(["ROLE_USER"])
                ->setPassword(
                    $this->encoder->hashPassword($user, 'Password1*')
                )
                ->setPhone('0000000000')
                ->setZipCode($faker->postcode())
                ->setCity('Dijon')
                ->setAddress($faker->streetAddress());

            $manager->persist($user);
        }

        // Entité PROJECT
        for ($i = 0; $i < 20; $i++) {
            $project = new Project();

            $project
                ->setName($faker->sentence(5))
                ->setDescription($faker->paragraph(50))
                ->setSector($faker->sentence(2))
                ->setUser($client);

            $manager->persist($project);
        }

        // Entité SERVICE
        for ($i = 0; $i < 20; $i++) {
            $service = new Service();

            $service
                ->setName($faker->sentence(5))
                ->setDescription($faker->paragraph(50))
                ->setSector($faker->sentence(2))
                ->setUser($consultant);

            $manager->persist($service);
        }

        $manager->flush();
    }
}
