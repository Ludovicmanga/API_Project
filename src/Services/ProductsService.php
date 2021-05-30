<?php

namespace App\Services;

use App\Entity\Products;
use App\Repository\ProductsRepository; 
use Doctrine\ORM\EntityManagerInterface;

Class ProductsService implements ProductsServiceInterface
{
    private $repository;
    
    public function __construct(
        ProductsRepository $repository,
        EntityManagerInterface $em 
    )
    {
        $this->repository = $repository;
        $this->em = $em;

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
        $this->em->flush();

        return new Response('Ok', 201);
    }
}
