<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{

   public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setNameOfProduct('product '.$i);
            $product->setStock(mt_rand(1, 100));
            $product->setDescriptionOfProduct('Very important product '.$i);
            $product->setPriceOfProduct(mt_rand(10, 1000));
            $manager->persist($product);
        }

        $manager->flush();
    }
}