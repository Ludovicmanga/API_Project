<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Subscribers;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Serializer\Serializer;
use App\Services\SubscribersServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Nelmio\ApiDocBundle\Annotation\Model;

class SubscriberController extends AbstractController
{
    private $subscribersService;

    public function __construct(
        SubscribersServiceInterface $subscribersService
    )
    {
        $this->subscribersService = $subscribersService;
    }

    /**
     * 
     * Allows to get the list of subscribers related to a user
     * 
     * @Route("api/subscriber/get/user/all",
     *    name="subscribers_users_get",
     *    methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the list of subscribers related to a user",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Subscribers::class))
     *     )
     * )
     * 
     * @OA\Parameter(
     *     name="user Id",
     *     in="query",
     *     description="The id of the user",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Tag(name="Subscriber")
     * 
     * @Security(name="Bearer")
     */
    public function getUserSubscribers(Request $request, User $user)
    {
        $subscribers = $this->subscribersService->findByUser($user);

        $response = new JsonResponse($this->subscribersService->serialize($subscribers));

        // We put the response in cache
        $response->setSharedMaxAge(1800);
        return $response;
    }

    /**
     * 
     * Allows to get a particular subscriber's information
     * 
     * @Route("/api/subscriber/get/{id}", 
     *    name="subscriber_get",
     *    methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the informations of a particular subscriber",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Subscribers::class))
     *     )
     * )
     * 
     * @OA\Parameter(
     *     name="subscriber Id",
     *     in="query",
     *     description="The id of the subscriber",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Tag(name="Subscriber")
     * 
     * @Security(name="Bearer")
     */
    public function getSubscriber(Subscribers $subscriber)
    {
        $response = new JsonResponse($this->subscribersService->serialize($subscriber));

        // We put the response in cache
        $response->setSharedMaxAge(1800);
        return $response;
    }

    /**
     * 
     * Allows to delete a subscriber
     * 
     * @Route("api/subscriber/remove/{id}", 
     *    name="subscriber_remove",
     *    methods={"DELETE"})
     * 
     * @OA\Response(
     *     response=204,
     *     description="Returns the informations of the success or failure of the deletion",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Subscribers::class))
     *     )
     * )
     * 
     * @OA\Parameter(
     *     name="subscriber Id",    
     *     in="query",
     *     description="The id of the subscriber",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Tag(name="Subscriber")
     *
     * @Security(name="Bearer") 
     */
    public function removeSubscriber(Subscribers $subscriber)
    {
        $this->subscribersService->remove($subscriber);
                
        $response = new JsonResponse('', 204);

        // We put the response in cache
        $response->setSharedMaxAge(1800);
        return $response;
    }

    /**
     * 
     * Allows to create a subscriber
     * 
     *@Route("api/subscriber/create", 
     *    name="subscriber_create",
     *    methods={"POST"})
     * 
     * @OA\Response(
     *     response=201,
     *     description="Returns the informations of the success or failure of the creation",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Subscribers::class))
     *     )
     * )
     * 
     * @OA\Parameter(
     *     name="subscriber name",
     *     in="query",
     *     description="The name of the subscriber",
     *     @OA\Schema(type="string")
     * )
     * 
     * @OA\Parameter(
     *     name="subscriber last name",
     *     in="query",
     *     description="The last name of the subscriber",
     *     @OA\Schema(type="string")
     * )
     * 
     * @OA\Parameter(
     *     name="subscriber email",
     *     in="query",
     *     description="The email of the subscriber",
     *     @OA\Schema(type="string")
     * )
     * 
     * @OA\Parameter(
     *     name="user Id",
     *     in="query",
     *     description="The id of the user related to this subscriber",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Tag(name="Subscriber")
     *  
     * @Security(name="Bearer")
     */
    public function addSubscriber(Request $request)
    {   
        return new JsonResponse($this->subscribersService->createSubscriber($request), 201);
    }
}
