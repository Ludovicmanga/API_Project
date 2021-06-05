<?php

namespace App\Services;

use App\Repository\UserRepository;

Interface SubscribersServiceInterface
{
    public function findByUser($userId); 

    public function remove($subscriber);

    public function serialize($subscribers);

    public function createSubscriber($request);
}