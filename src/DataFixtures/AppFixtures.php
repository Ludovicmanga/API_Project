<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Clients;
use App\Entity\Products;
use App\Entity\Subscribers;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder; 
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        // creation of 10 products
        for ($i = 0; $i < 10; $i++) {
            $product = new Products();
            $product->setName('product'.$i);
            $manager->persist($product);
        }

        // creation of 10 clients, each of them with 5 subscribers
        for ($i = 0; $i < 10; $i++) {
            $client = new Clients();
            $client->setName($faker->company());
            $client->setEmail($faker->email());
            $client->setPassword('123456');

            $subscriber1 = new Subscribers(); 
            $subscriber1->setClient($client); 
            $subscriber1->setName($faker->name()); 
            $subscriber1->setLastName($faker->lastName());
            $subscriber1->setEmail($faker->email());

            $subscriber2 = new Subscribers(); 
            $subscriber2->setClient($client); 
            $subscriber2->setName($faker->name()); 
            $subscriber2->setLastName($faker->lastName());
            $subscriber2->setEmail($faker->email());

            $subscriber3 = new Subscribers(); 
            $subscriber3->setClient($client); 
            $subscriber3->setName($faker->name()); 
            $subscriber3->setLastName($faker->lastName());
            $subscriber3->setEmail($faker->email());

            $subscriber4 = new Subscribers(); 
            $subscriber4->setClient($client); 
            $subscriber4->setName($faker->name()); 
            $subscriber4->setLastName($faker->lastName());
            $subscriber4->setEmail($faker->email());

            $subscriber5 = new Subscribers(); 
            $subscriber5->setClient($client); 
            $subscriber5->setName($faker->name()); 
            $subscriber5->setLastName($faker->lastName());
            $subscriber5->setEmail($faker->email());

            $manager->persist($subscriber1);
            $manager->persist($subscriber2);
            $manager->persist($subscriber3);
            $manager->persist($subscriber4);
            $manager->persist($subscriber5);
            $manager->persist($client);
        }

        $manager->flush();
    }
}
