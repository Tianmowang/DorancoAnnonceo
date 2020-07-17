<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Categorie as EntityCategorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        
        $faker = Faker\Factory::create('fr_FR');
        

        for ($i=1; $i < 16; $i++) { 
            $product = new EntityCategorie();
            $title = $faker->sentence(3);

            $product->setNom($title);

            /* $product->setTitle('Article n° ' . $i)
                    ->setContent('<p>Ceci est le content de l\'article</p>')
                    ->setImage('https://via.placeholder.com/300X200')
                    ->setIntro('Ceci est une super intro')
                    ->setCreatedAt(new \DateTime()); */

    
            $manager->persist($product);
        }

        $manager->flush();
    }
}
