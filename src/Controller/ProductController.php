<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Products;
use App\Services\ProductsServiceInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class ProductController extends AbstractController
{
    private $productsService;

    public function __construct(
        ProductsServiceInterface $productsService
    )
    {
        $this->productsService = $productsService;
    }

    /**
     * 
     * Allows to get the list of all the products
     * 
     *@Route("/api/product/list",
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
     * 
     * @OA\Tag(name="Product")
     * 
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
     * @Route("/api/product/{id}", 
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
     * @OA\Tag(name="Product")
     * 
     * @Security(name="Bearer")
     */
    public function getProduct(Products $product)
    {
        return New JsonResponse($this->productsService->serialize($product));
    }
}
