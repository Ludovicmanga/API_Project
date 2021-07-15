<?php

namespace App\Controller;

use App\Entity\User;
use App\Api\ApiProblem;
use Pagerfanta\Pagerfanta;
use App\Entity\Subscribers;
use OpenApi\Annotations as OA;
use App\Api\ApiProblemException;
use App\Pagination\PaginatedCollection;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use App\Services\SerializerServiceInterface;
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

    public function __construct(SubscribersServiceInterface $subscribersService)
    {
        $this->subscribersService = $subscribersService;
    }

    protected function createApiResponse($data, $statusCode = 200)
    {   
        $json = $this->subscribersService->serialize($data);
        return new Response($json, $statusCode, array(
            'Content-Type' => 'application/json'
        ));
    }

    /**
     * 
     * Allows to get the list of subscribers related to a user
     * 
     * @Route("/api/subscriber/get/user/all",
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
    public function getUserSubscribers(Request $request)
    {
        $user = $this->getUser();
        $page = $request->query->get('page', 1);
        
        $qb = $this->subscribersService->findByUserQueryBuilder($user);
        
        $adapter = new QueryAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($page);

        $subscribers = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $subscribers[] = $result;
        }

         $response = $this->createApiResponse([
            'total' => $pagerfanta->getNbResults(),
            'count' => count($subscribers),
            'items' => $subscribers,
        ], 200);

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
        $user = $this->getUser();

        // We make sure the subscriber is related to the user, otherwise a 403 exception is thrown
        if($subscriber->getUser() == $user)
        {
            $response = $this->createApiResponse($subscriber);

            // We put the response in cache
            $response->setSharedMaxAge(1800);

            return $response;
        } else {
            $apiProblem = new ApiProblem(403);
    
            throw new ApiProblemException($apiProblem);
        }
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
    public function remove(Subscribers $subscriber)
    {
        $user = $this->getUser();

        // We make sure the subscriber is related to the user, otherwise a 403 exception is thrown
        if($subscriber->getUser() == $user)
        {
            $this->subscribersService->remove($subscriber);

            $response = $this->createApiResponse('', 204);

            // We put the response in cache
            $response->setSharedMaxAge(1800);
            return $response;

        } else {
            $apiProblem = new ApiProblem(403);
    
            throw new ApiProblemException($apiProblem);
        }
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
     * 
     * @OA\Tag(name="Subscriber")
     *  
     * @Security(name="Bearer")
     */
    public function add(Request $request)
    {   
        // We get the user Id to make sure he/she only can add related to him / her
        $user = $this->getUser();

        $subscriber = $this->subscribersService->createSubscriber($request, $user);

        return $this->createApiResponse($subscriber, 201);
    }
    /**
     * 
     * Allows to edit a subscriber
     * 
     *@Route("api/subscriber/edit/{id}", 
     *    name="subscriber_create",
     *    methods={"PUT"})
     * 
     * @OA\Response(
     *     response=201,
     *     description="Returns the informations of the success or failure of the edition",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Subscribers::class))
     *     )
     * )
     * 
     * @OA\Parameter(
     *     name="subscriber id",
     *     in="query",
     *     description="The id of the subscriber",
     *     @OA\Schema(type="integer")
     * )
     * 
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
     * 
     * @OA\Tag(name="Subscriber")
     *  
     * @Security(name="Bearer")
     */
    public function edit(?Subscribers $subscriber, Request $request)
    {   
        // We get the user Id to make sure he/she only can add related to him / her
        $user = $this->getUser();

        // We make sure the subscriber is related to the user, otherwise a 403 exception is thrown
        if($subscriber->getUser() == $user) { 
            $subscriber = $this->subscribersService->editSubscriber($request, $user, $subscriber);
            
            return $this->createApiResponse($subscriber, 201);
        } else {
            $apiProblem = new ApiProblem(403);
    
            throw new ApiProblemException($apiProblem);
        }
    }
}
