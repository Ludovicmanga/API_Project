<?php

namespace App\Controller;

use App\Entity\Subscribers;
use Symfony\Component\Serializer\Serializer;
use App\Services\SubscribersServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *@Route("/subscriber", name="subscriber_")
    */
class SubscriberController extends AbstractController
{
    private $subscribersService;

    public function __construct(SubscribersServiceInterface $subscribersService)
    {
        $this->subscribersService = $subscribersService;
    }

    /**
     *@Route("/get/user/{user_id}", 
     *    name="getUserSubscribers",
     *    methods={"GET"})
     */
    public function getUserSubscribers(Request $request)
    {
        $userId = $request->get('user_id');
        $subscribers = $this->subscribersService->findByUser($userId);

        return $this->subscribersService->serialize($subscribers); 
    }

    /**
     *@Route("/get/{id}", 
     *    name="getSubscriber",
     *    methods={"GET"})
     */
    public function getSubscriber(Subscribers $subscriber)
    {
        return $this->subscribersService->serialize($subscriber); 
    }

    /**
     *@Route("/remove/{id}", 
     *    name="remove",
     *    methods={"DELETE"})
     */
    public function remove(Subscribers $subscriber)
    {
        $this->subscribersService->remove($subscriber);
    }
}
