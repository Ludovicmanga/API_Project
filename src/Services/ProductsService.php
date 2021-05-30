<?php

namespace App\Services;

use App\Repository\ProductsRepository; 

Class ProductsService implements ProductsServiceInterface
{
    private $repository;
    
    public function __construct(ProductsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function findAll()
    {
        return $this->repository->findAll(); 
    }
}
