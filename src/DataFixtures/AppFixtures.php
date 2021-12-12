<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Resume;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

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



        // Entité Article
        for ($i = 0; $i < 50; $i++) {
            $article = new Article();

            $article
                ->setName($faker->sentence(5))
                ->setDescription($faker->paragraph(50))
                ->setSector($faker->sentence(2))
                ->setUser($admin);

            $manager->persist($article);
        }

        // Entité resume
        for ($i = 0; $i < 50; $i++) {
            $resume = new Resume();

            $resume
                ->setName($faker->sentence(5))
                ->setDescription($faker->paragraph(50))
                ->setSector($faker->sentence(2))
                ->setUser($client);

            $manager->persist($resume);
        }

        $manager->flush();
    }
}
