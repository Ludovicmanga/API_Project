<?php

namespace App\Services;

use App\Entity\Products;
use App\Entity\Subscribers;
use App\Repository\UserRepository;
use App\Services\MainServiceInterface;
use App\Services\UserServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubscribersRepository;
use App\Services\SerializerServiceInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

Class SubscribersService implements SubscribersServiceInterface
{
    public function __construct(
        SubscribersRepository $repository,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserServiceInterface $userService,
        SerializerServiceInterface $serializerService,
        MainServiceInterface $mainService
    )
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->userService = $userService;
        $this->serializerService = $serializerService;
        $this->mainService = $mainService;
    }

    public function remove($subscriber)
    {
        $this->em->remove($subscriber);
        $this->em->flush();
    }

    public function findByUser($user)
    {
       return $this->repository->findByUser($user);
    }

    public function serialize($subscribers)
    {
        return $this->serializerService->serialize($subscribers);
    }

    public function createSubscriber($request, $user)
    {
        $subscriber = new Subscribers;
        $this->mainService->submit($subscriber, 'subscriber-create', $request, $user);

        $this->em->persist($subscriber);
        $this->em->flush();
        
        return $subscriber;
    }

    public function editSubscriber($request, $user, $subscriber)
    {   
        if($subscriber === null) {
            $this->createSubscriber($request, $user);
        } else {
            $this->mainService->submit($subscriber, 'subscriber-create', $request, $user);

            $this->em->persist($subscriber);
            $this->em->flush();
            
            return $subscriber;
        }
    }

    public function findByUserQueryBuilder($user)
    {
        return $this->repository->findByUserQueryBuilder($user);
    }
}
