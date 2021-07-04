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

    public function createSubscriber($request)
    {
        //$data = json_decode($request->getContent());
        $formName = 'subscriber-create';
        $subscriber = new Subscribers;
        $dataArray = $this->mainService->submit($subscriber, $formName, $request);
        /* $subscriber
            ->setName($data->name)
            ->setLastName($data->lastName)
            ->setEmail($data->email)
        ;

        $userId = $data->userId;
        $userIdInt = intval($userId);

        // créer un formtype pour valider les données, en mettant des contraintes.

        $user = $this->userRepository->find($userIdInt);
        $subscriber->setUser($user); */

        $subscriber
            ->setName($dataArray['name'])
            ->setLastName($dataArray['lastName'])
            ->setEmail($dataArray['email'])
        ;

        $userId = $dataArray['user'];
        $userIdInt = intval($userId);

        $user = $this->userRepository->find($userIdInt);
        $subscriber->setUser($user);

        $this->em->persist($subscriber);
        $this->em->flush();
        
        return $this->serializerService->serialize($subscriber);
    }
}
