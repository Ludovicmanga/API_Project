<?php

namespace App\Services;

Interface ProductsServiceInterface
{
    public function findAll();

    public function createProduct($request);
}