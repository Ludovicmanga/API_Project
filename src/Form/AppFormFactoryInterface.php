<?php

namespace App\Form;

use Symfony\Component\Form\FormFactoryInterface;

Interface AppFormFactoryInterface
{
    public function __construct(FormFactoryInterface $formFactory);

    public function create($name, $object);
}