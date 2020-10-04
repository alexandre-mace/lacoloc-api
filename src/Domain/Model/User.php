<?php


namespace App\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;

class User
{
    /**
     * @Assert\Type("string")
     * @Assert\NotNull()
     */
    public $name;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    public $homeId;
}