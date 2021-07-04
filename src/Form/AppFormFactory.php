<?php

namespace App\Form; 

use Symfony\Component\Form\FormFactoryInterface; 

class AppFormFactory implements AppFormFactoryInterface
{
    private $formFactory; 

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory; 
    }

    public function create($name, $object)
    {
        switch ($name) {
            //form 
            case 'subscriber-create': 
                $form = SubscriberCreateType::class; 
                break;
            //default
            default: 
                $form = null; 
                break; 
        }   

        if (null !== $form) {
            return $this->formFactory->create($form, $object); 
        }

        return false; 
    }
}
