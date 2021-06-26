<?php

namespace App\Services;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SerializerService implements SerializerServiceInterface
{
    public function serialize($objectToSerialize)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        return $serializer->serialize($objectToSerialize, 'json', [
            'circular_reference_handler' => function($object){
                return $object->getId();
            }
        ]);
    }
}
