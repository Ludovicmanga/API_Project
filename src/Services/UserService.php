<?php

namespace App\Services;

use App\Entity\Users;
use App\Repository\UserRepository; 

Class UserService implements UserServiceInterface
{
    private $repository;
    
    public function __construct(
        UserRepository $repository
    )
    {
        $this->repository = $repository;
    }

    public function findById($userId)
    {
        $this->repository->findById($userId); 
    }

}
