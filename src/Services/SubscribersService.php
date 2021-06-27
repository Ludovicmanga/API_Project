<?php

namespace App\Services;

use App\Entity\Products;
use App\Entity\Subscribers;
use App\Services\SerializerServiceInterface;
use App\Repository\UserRepository;
use App\Services\UserServiceInterface;
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
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserServiceInterface $userService,
        SerializerServiceInterface $serializerService
    )
    {
        $this->repository = $repository;
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->userService = $userService;
        $this->serializerService = $serializerService;
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

    public function createSubscriber($request)
    {
        $data = json_decode($request->getContent());
        $subscriber = New Subscribers;
        $subscriber->setName($data->name);
        $subscriber->setLastName($data->lastName);
        $subscriber->setEmail($data->email);

        $userId = $data->userId;
        $userIdInt = intval($userId);
        $user = $this->userRepository->find($userIdInt);
        $subscriber->setUser($user);

        $this->em->persist($subscriber);
        $this->em->flush();
    }
}
