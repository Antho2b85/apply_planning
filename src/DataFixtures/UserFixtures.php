<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use \Symfony\Component\String\Slugger\AsciiSlugger;
use Exception;
use Faker;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    /**@throws Exception */
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        $equipes = ['1','2'];

// Fonction pour générer le mail entreprise
$slugger = new AsciiSlugger();

    $genererEmail = function($prenom, $nom) use ($slugger) {
        $prenomClean = strtolower($slugger->slug($prenom));
        $nomClean = strtolower($slugger->slug($nom));
        
        return $prenomClean . '.' . $nomClean . '@corsicalinea.com';
    };
    

// ======================================
// Fixture for Responsable
// ======================================
        $responsable = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($responsable, 'admin123');
        $nom = 'Papi';
        $prenom = 'Claude';

        $responsable->setNom($nom)
                    ->setPrenom($prenom)
                ->setEmail($genererEmail($prenom, $nom))
                ->setFirstLogin(false)
                ->setRoles(['ROLE_ADMIN'])
                ->setPassword($hashedPassword);

                $manager->persist($responsable);
                $this->addReference('user-responsable', $responsable);


// ======================================
// Fixture for Chef d'equipe
// ======================================
        $chef = new User();
        $hashedPassword = $this->passwordHasher->hashPassword($chef, 'chef23');
        $nom = 'Cesari';
        $prenom = 'Armand';

            $chef->setNom($nom)
                ->setPrenom($prenom)
                ->setEmail($genererEmail($prenom, $nom))
                ->setFirstLogin(false)
                ->setRoles(['ROLE_CHEF'])
                ->setPassword($hashedPassword)
                ->setEquipe($faker->randomElement($equipes));

                $manager->persist($chef);
                $this->addReference('user-chef-1', $chef);


// ======================================
// Fixture for Users
// ======================================
        for ($i = 1; $i <= 14; $i++){
           $user = new User();
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');

            $fakerNom = $faker->lastName();
            $fakerPrenom = $faker->firstName();
            
            $user->setNom($fakerNom)
                ->setPrenom($fakerPrenom)
                ->setEmail($genererEmail($fakerPrenom, $fakerNom))
                ->setEquipe($faker->randomElement($equipes))
                ->setFirstLogin(true)
                ->setRoles(['ROLE_USER'])
                ->setPassword($hashedPassword);
            
            $manager->persist($user);
            $this->addReference('user-employe-' .$i, $user);
        }
        $manager->flush();
    }
}
