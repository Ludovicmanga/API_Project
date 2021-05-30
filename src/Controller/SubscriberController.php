<?php

namespace App\Controller;

use App\Entity\Subscribers;
use App\Services\SubscribersServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
     *@Route("/remove/{id}", 
     *    name="remove",
     *    methods={"DELETE"})
     */
    public function remove(Subscribers $subscriber)
    {
        $this->subscribersService->remove($subscriber);
    }
}
