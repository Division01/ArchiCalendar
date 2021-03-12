<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ArticleSemaine;
use App\Entity\Tache;

class ArtSemFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = \Faker\Factory::create('fr_FR');

        // Cree entre 2 et 4 fakes semaines
        for($j =1; $j <= mt_rand(2,4); $j++){
            $articleSemaine = new ArticleSemaine();

            $articleSemaine->setTitle("Semaine n°$j")
                           ->setContent("<p>Description de la semaine n°$j</p>")
                           ->setCreatedAt($faker->dateTimeBetween('-5 months'));

            $manager->persist($articleSemaine);
        

            // Créer 3 taches fakes par article
            for($i = 1; $i <= 3; $i++){
                $tache = new Tache();

                $description = '<p>' . join($faker->paragraphs(5),'</p><p>') . '</p>';

                $tache->setDescription($description);
                $tache->setDueDate($faker->dateTimeBetween('-6 months', '+1 months'));
                $tache->setDone(FALSE);
                $tache->setSemaine($articleSemaine);

                $manager->persist($tache);
            }
        }


        $manager->flush();          #Envoie la requete SQL
    }
}
