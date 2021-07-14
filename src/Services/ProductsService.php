<?php

namespace App\Services;

use App\Entity\Products;
use App\Repository\ProductsRepository; 
use Doctrine\ORM\EntityManagerInterface;
use App\Services\SerializerServiceInterface;
use Symfony\Component\HttpFoundation\Response;

Class ProductsService implements ProductsServiceInterface
{
    private $repository;
    
    public function __construct(
        ProductsRepository $repository,
        EntityManagerInterface $em,
        SerializerServiceInterface $serializerService
    )
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->serializerService = $serializerService;
    }

    public function findAll()
    {
        return $this->repository->findAll(); 
    }

    public function createProduct($request)
    {
        $data = json_decode($request->getContent());
        
        $product = New Products;
        $product->setName($data->name);
        $this->em->persist($product);
        $this->em->flush();

        return new Response('Ok', 201);
    }

    public function serialize($products)
    {
        return $this->serializerService->serialize($products);
    }

    public function findAllQb()
    {
        return $this->repository->findAllQb(); 
    }
}
