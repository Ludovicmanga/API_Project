<?php

namespace App\DataFixtures;

use App\Entity\Products;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        // creation of 10 products
        for ($i = 0; $i < 10; $i++) {
            $product = new Products();
            $product->setName('product'.$i);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
