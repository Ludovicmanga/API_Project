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
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($products, 'json', [
            'circular_reference_handler' => function($object){
                return $object->getId();
            }
        ]);
        
        $response = New Response($jsonContent);
        $response->headers->set('Content-type', 'application/json');

        return $response;
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
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($product, 'json', [
            'circular_reference_handler' => function($object){
                return $object->getId();
            }
        ]);
        
        $response = New Response($jsonContent);

        $response->setCache([
            'must_revalidate'  => false,
            'no_cache'         => false,
            'no_store'         => false,
            'no_transform'     => false,
            'public'           => true,
            'private'          => false,
            'proxy_revalidate' => false,
            'max_age'          => 600,
            's_maxage'         => 600,
            'immutable'        => true,
            'last_modified'    => new \DateTime(),
            'etag'             => 'product',
        ]);
        
        $response->headers->set('Content-type', 'application/json');

        return $response;
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
        return $this->subscribersService->serialize($subscriber); 
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
