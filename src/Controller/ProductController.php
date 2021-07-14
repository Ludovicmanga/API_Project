<?php

namespace App\Controller;

use App\Entity\User;
use App\Api\ApiProblem;
use App\Entity\Products;
use Pagerfanta\Pagerfanta;
use OpenApi\Annotations as OA;
use App\Api\ApiProblemException;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\Form\FormInterface;
use App\Services\ProductsServiceInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;use Nelmio\ApiDocBundle\Annotation\Model;

class ProductController extends AbstractController
{
    private $productsService;

    public function __construct(
        ProductsServiceInterface $productsService
    )
    {
        $this->productsService = $productsService;
    }

    protected function createApiResponse($data, $statusCode = 200)
    {   
        $json = $this->productsService->serialize($data);
        return new Response($json, $statusCode, array(
            'Content-Type' => 'application/json'
        ));
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
    public function getProductList(Request $request)
    {
        $page = $request->query->get('page', 1);
        
        $qb = $this->productsService->findAllQb();

        $adapter = new QueryAdapter($qb);
        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($page);

        $products = [];
        foreach ($pagerfanta->getCurrentPageResults() as $result) {
            $products[] = $result;
        }

         $response = $this->createApiResponse([
            'total' => $pagerfanta->getNbResults(),
            'count' => count($products),
            'items' => $products,
        ]);

        //$response = New JsonResponse($this->productsService->serialize($products));

        // We put the response in cache
        $response->setSharedMaxAge(1800);
        return $response;
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
        $response = $this->createApiResponse($product);

        // We put the response in cache
        $response->setSharedMaxAge(1800);
        return $response;
    }
}
