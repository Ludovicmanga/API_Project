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
 *@Route("/api", 
 *    name="api_")
*/
class ApiController extends AbstractController
{
    private $productService;

    public function __construct(ProductsServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     *@Route("/create/product", 
     *    name="product_create",
     *    methods={"POST"})
     */
    public function addProduct(Request $request, EntityManagerInterface $em)
    {
        //if($request->isXmlHttpRequest()){
            $data = json_decode($request->getContent());
            $product = New Products;
            $product->setName($data->name);
            $em->persist($product);
            $em->flush();

            return new Response('Ok', 201);
        //}
        //return new Response('Erreur', 404);
    }

    /**
     *@Route("/products/list", 
     *    name="product_list", 
     *    methods={"GET"})
     */
    public function getList()
    {
        $products = $this->productService->findAll();

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

        $response->headers->set('Content-type', 'application/json');

        return $response;
    }
}
