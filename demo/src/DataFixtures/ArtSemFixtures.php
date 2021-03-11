<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\ArticleSemaine;

class ArtSemFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i =1; $i <= 5; $i++){
            $articleSemaine = new ArticleSemaine();
            $articleSemaine->setTitle("Semaine n°$i")
                           ->setContent("<p>Contenu de l'article n°$i</p>")
                           ->setCreatedAt(new \DateTime());

            $manager->persist($articleSemaine);
        }

        $manager->flush();          #Envoie la requete SQL
    }
}
