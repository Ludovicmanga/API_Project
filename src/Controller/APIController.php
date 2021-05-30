<?php

namespace App\Controller;

use App\Services\ProductsServiceInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *@Route("/api", name="api_")
*/
class ApiController extends AbstractController
{
    /**
     *@Route("/products/list", name="list")
     */
    public function list(ProductsServiceInterface $productService)
    {
        $products = $productService->findAll();

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
}
