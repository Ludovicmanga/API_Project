<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use App\Services\ProductsServiceInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *@Route("/product", 
 *    name="product_")
*/
class ProductController extends AbstractController
{
    private $productsService;

    public function __construct(ProductsServiceInterface $productsService)
    {
        $this->productsService = $productsService;
    }

    /**
     *@Route("/create", 
     *    name="product_create",
     *    methods={"POST"})
     */
    public function add(Request $request)
    {
        //if($request->isXmlHttpRequest()){
            return $this->productsService->createProduct($request);
        //}
        //return new Response('Erreur', 404);
    }

    /**
     *@Route("/list", 
     *    name="product_list", 
     *    methods={"GET"})
     */
    public function getList()
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
     *@Route("/{id}", 
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

        $response->headers->set('Content-type', 'application/json');

        return $response;
    }

}
