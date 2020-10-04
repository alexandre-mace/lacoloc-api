<?php


namespace App\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Home
{
    /**
     * @Assert\Type("string")
     * @Assert\NotNull()
     */
    public $name;

    public $uuid;
}