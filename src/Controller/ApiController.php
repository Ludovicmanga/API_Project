<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Products;
use App\Entity\Subscribers;
use App\Services\ProductsServiceInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Component\Serializer\Serializer;
use App\Services\SubscribersServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

/**
 *@Route("/api", 
 *    name="api_")
*/
class ApiController extends AbstractController
{
    private $productsService;
    private $subscribersService;

    public function __construct(
        ProductsServiceInterface $productsService, 
        SubscribersServiceInterface $subscribersService,
        CacheInterface $cache
    )
    {
        $this->productsService = $productsService;
        $this->subscribersService = $subscribersService;
        $this->cache = $cache;
    }

    /**
     * 
     * Allows to get the list of all the products
     * 
     *@Route("/product/list",
     *    name="product_list",
     *    methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the products list",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Products::class))
     *     )
     * )
     * @Security(name="Bearer")
     */
    public function getProductList()
    {
        $products = $this->productsService->findAll();

       //return $this->cache->get('product_list', function(ItemInterface $item, $products){
       //     $item->expiresAfter(10);
          return New JsonResponse($this->productsService->serialize($products)); 
       // });
    }

    /**
     * Allows to get a particular product's information
     * 
     * @Route("/product/{id}", 
     *    name="product", 
     *    methods={"GET"})
     * 
     * @OA\Response(
     *     response=200,
     *     description="Returns the informations of a particular product",
     *     @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=Products::class))
     *     )
     * )
     * 
     * @OA\Parameter(
     *     name="product Id",
     *     in="query",
     *     description="The id of the product",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @Security(name="Bearer")
     */
    public function getProduct(Products $product)
    {
        return New JsonResponse($this->productsService->serialize($product));
    }
    
    /**
     * 
     * Allows to get the list of subscribers related to a user
     * 
     * @Route("/subscriber/get/user/{user}",
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
     * @Security(name="Bearer")
     */
    public function getUserSubscribers(Request $request, User $user)
    {
        $subscribers = $this->subscribersService->findByUser($user);

        return new JsonResponse($this->subscribersService->serialize($subscribers)); 
    }

    /**
     * 
     * Allows to get a particular subscriber's information
     * 
     * @Route("/subscriber/get/{id}", 
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
     * @Security(name="Bearer")
     */
    public function getSubscriber(Subscribers $subscriber)
    {
        return new JsonResponse($this->subscribersService->serialize($subscriber)); 
    }

    /**
     * 
     * Allows to delete a subscriber
     * 
     * @Route("/subscriber/remove/{id}", 
     *    name="subscriber_remove",
     *    methods={"DELETE"})
     * 
     * @OA\Response(
     *     response=200,
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
     * @Security(name="Bearer") 
     */
    public function removeSubscriber(Subscribers $subscriber)
    {
        $this->subscribersService->remove($subscriber);
                
        return new Response('ok');
    }

    /**
     * 
     * Allows to create a subscriber
     * 
     *@Route("/subscriber/create", 
     *    name="subscriber_create",
     *    methods={"POST"})
     * 
     * @OA\Response(
     *     response=200,
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
     * @Security(name="Bearer")
     */
    public function addSubscriber(Request $request)
    {
            $this->subscribersService->createSubscriber($request);
            
            return new Response('Ok', 201);  
    }
}
