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
     *@Route("/get/{user_id}", 
     *    name="getUserSubscribers",
     *    methods={"GET"})
     */
    public function getUserSubscribers(Request $request)
    {
        $userId = $request->get('user_id');
        $subscribers = $this->subscribersService->findByUser($userId);

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($subscribers, 'json', [
            'circular_reference_handler' => function($object){
                return $object->getId();
            }
        ]);
        
        $response = New Response($jsonContent);

        $response->headers->set('Content-type', 'application/json');

        return $response;
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
