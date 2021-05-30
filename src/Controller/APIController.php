<?php

namespace App\Controller;

use App\Services\ProductsServiceInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 *@Route("/api", name="api_")
*/
class ApiController extends AbstractController
{
    /**
     *@Route("/list", name="list")
     */
    public function list(ProductsServiceInterface $productService): Response
    {
        $products = $productService->findAll();
        dd($products);

        return $this->render('api/index.html.twig', [
            'controller_name' => 'ApiController',
        ]);
    }

}
