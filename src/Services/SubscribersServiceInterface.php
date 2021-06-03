<?php

namespace App\Services;

Interface SubscribersServiceInterface
{
    public function findByUser($userId); 

    public function remove($subscriber);
}