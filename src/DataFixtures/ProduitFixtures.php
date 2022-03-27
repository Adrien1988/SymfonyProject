<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProduitFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $faker = \Faker\Factory::create('fr_FR');

    for ($h = 1; $h <= 5; $h++) {
      $auteur = new User;
      $auteur->setUsername($faker->name)
        ->setEmail($faker->safeEmail)
        ->setPassword($faker->password);

      $manager->persist($auteur);

      for ($i = 1; $i <= 3; $i++) {
        $categorie = new Categorie;
        $categorie->setTitre($faker->sentence());

        $manager->persist($categorie);

        for ($j = 1; $j <= mt_rand(5, 10); $j++) {
          $produit = new Produit;

          $contenu = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';


          $produit->setNom($faker->sentence(3))
            ->setDescription($contenu)
            ->setPrix($faker->randomFloat(2, 30, 100))
            ->setImage("https://picsum.photos/200/150")
            ->setCategorie($categorie)
            ->setAuteur($auteur);

          $manager->persist($produit);
        }
      }
    }
    $manager->flush();
  }
}
