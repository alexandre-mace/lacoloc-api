<?php


namespace App\Serializer;


class SafeSerializerValidationException extends \Exception
{
    public $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
        parent::__construct();
    }
}