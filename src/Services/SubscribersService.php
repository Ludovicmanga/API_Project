<?php

namespace App\Services;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubscribersRepository; 
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

Class SubscribersService implements SubscribersServiceInterface
{
    public function __construct(
        SubscribersRepository $repository,
        EntityManagerInterface $em
    )
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    public function remove($subscriber)
    {
        $this->em->remove($subscriber);
        $this->em->flush();
        
        return new Response('ok');
    }

    public function findByUser($userId)
    {
       return $this->repository->findByUser($userId); 
    }

    public function serialize($subscribers)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($subscribers, 'json', [
            'circular_reference_handler' => function($object){
                return $object->getId();
            }
        ]);
        
        $response = New Response($jsonContent);

        $response->headers->set('Content-type', 'application/json');

        return $response;
    }
}