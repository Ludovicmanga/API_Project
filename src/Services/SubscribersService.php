<?php

namespace App\Services;

use App\Entity\Products;
use App\Repository\SubscribersRepository; 
use Doctrine\ORM\EntityManagerInterface;

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
}