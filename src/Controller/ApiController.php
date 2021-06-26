<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\Subscribers;
use App\Services\ProductsServiceInterface;
use Symfony\Component\Serializer\Serializer;
use App\Services\SubscribersServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
        SubscribersServiceInterface $subscribersService
    )
    {
        $this->productsService = $productsService;
        $this->subscribersService = $subscribersService;
    }

    /**
     *@Route("/product/list",
     *    name="product_list",
     *    methods={"GET"})
     */
    public function getProductList()
    {
        $products = $this->productsService->findAll();
        
        return New JsonResponse($this->productsService->serialize($products));
    }

    /**
     * Allows to get a particular product
     * 
     *@Route("/product/{id}", 
     *    name="product", 
     *    methods={"GET"})
     */
    public function getProduct(Products $product)
    {
        return New JsonResponse($this->productsService->serialize($product));
    }
    
    /**
     *@Route("/subscriber/get/user/{user_id}", 
     *    name="subscribers_users_get",
     *    methods={"GET"})
     */
    public function getUserSubscribers(Request $request)
    {
        $userId = $request->get('user_id');
        $subscribers = $this->subscribersService->findByUser($userId);

        return $this->subscribersService->serialize($subscribers); 
    }

    /**
     *@Route("/subscriber/get/{id}", 
     *    name="subscriber_get",
     *    methods={"GET"})
     */
    public function getSubscriber(Subscribers $subscriber)
    {
        return new JsonResponse($this->subscribersService->serialize($subscriber)); 
    }

    /**
     *@Route("/subscriber/remove/{id}", 
     *    name="subscriber_remove",
     *    methods={"DELETE"})
     */
    public function removeSubscriber(Subscribers $subscriber)
    {
        return $this->subscribersService->remove($subscriber);
    }

    /**
     *@Route("/subscriber/create", 
     *    name="subscriber_create",
     *    methods={"POST"})
     */
    public function addSubscriber(Request $request)
    {
        //if($request->isXmlHttpRequest()){
            return $this->subscribersService->createSubscriber($request); 
        //}
        //return new Response('Erreur', 404);
    }
}
