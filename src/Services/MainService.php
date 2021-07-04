<?php

namespace App\Services;

use App\Entity\Subscribers;
use App\Form\AppFormFactoryInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class MainService implements MainServiceInterface
{
    private $formFactory;

    public function __construct(AppFormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }
    
    public function submit($object, $formName, $data)
    {
        $dataArray = json_decode($data->getContent(), true);

        // Bad array
        if (null !== $data && !is_array($dataArray)){
            throw new UnprocessableEntityHttpException('Submitted data is not an array ->'.$data);
        }

        //submit form
        $form = $this->formFactory->create($formName, $object);
        $form->submit($dataArray, false);

        //gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            throw new LogicException('Error '.get_class($error->getCause()). ' --> ' . $error->getMessageTemplate() .' '. json_encode($error->getMessageParameters())); 
        }

        //sets fields to null
        if (is_array($dataArray))
        {
            foreach($dataArray as $key => $value) {
                if(null === $value || 'null' === $value){
                    $method = 'set'.ucfirst($key);
                    if ($method_exists($object, $method)) {
                        $object->$method(null); 
                    }
                }
            }
        }
        
        return $dataArray;
    }
}