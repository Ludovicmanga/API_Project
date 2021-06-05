<?php

namespace App\Services;

use App\Entity\Products;
use App\Repository\ProductsRepository; 
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

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
        dd($data);
        $product = New Products;
        $product->setName($data->name);
        $this->em->persist($product);
        $this->em->flush();

        return new Response('Ok', 201);
    }
}
