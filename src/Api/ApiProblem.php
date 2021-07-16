<?php

namespace App\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Exception\InvalidArgumentException;
/**
 * A wrapper for holding data to be used for a application/problem+json response
 */
class ApiProblem
{
    private $statusCode;
    private $type;
    private $title;
    private $extraData = array();

    const TYPE_VALIDATION_ERROR = 'validation_error';
    const FORBIDDEN_USER_EDIT = 'forbidden_user_edit';
    const FORBIDDEN_USER_REMOVE = 'forbidden_user_remove';
    CONST FORBIDDEN_USER_GET = 'forbidden_user_get';
    const FORBIDDEN = 'forbidden';
    const TYPE_INVALID_REQUEST_BODY_FORMAT = 'invalid_body_format';
    const NOT_ALLOWED_METHOD = 'not_allowed_method';

    private static $titles = array(
        self::FORBIDDEN_USER_EDIT => 'You cannot edit this subscriber because it belongs to another user',
        self::FORBIDDEN_USER_GET => 'You cannot get this subscriber because it belongs to another user',
        self::FORBIDDEN_USER_REMOVE => 'You cannot remove this subscriber because it belongs to another user',
        self::TYPE_VALIDATION_ERROR => 'There was a validation error',
        self::FORBIDDEN => 'You cannot access to this ressource',
        self::TYPE_INVALID_REQUEST_BODY_FORMAT => 'Invalid JSON format sent',
    );

    public function __construct($statusCode, $type = null)
    {
        $this->statusCode = $statusCode;
        
        if ($type === null) {

            switch ($this->statusCode) {
                 
                case 403: 
                    $type =self::FORBIDDEN; 
                    break;
                case 405: 
                    $type =self::NOT_ALLOWED_METHOD; 
                    break;
                //default
                default: 
                $type = 'about:blank'; 
                    break; 
            }

            // no type? The default is about:blank and the title should
            // be the standard status code message
            
            $title = isset(Response::$statusTexts[$statusCode])
                ? Response::$statusTexts[$statusCode]
                : 'Unknown status code :(';
        } else {
            if (!isset(self::$titles[$type])) {
                throw new \InvalidArgumentException('No title for type '.$type);
            }
            $title = self::$titles[$type];
        }

        $this->type = $type;
        $this->title = $title;
    }

    public function set($name, $value)
    {
        $this->extraData[$name] = $value;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function toArray()
    {
        return array_merge(
            $this->extraData,
            array(
                'status' => $this->statusCode,
                'type' => $this->type,
                'title' => $this->title,
            )
        );
    }
}
