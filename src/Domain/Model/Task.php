<?php


namespace App\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Task
{
    /**
     * @Assert\Type("string")
     * @Assert\NotNull()
     */
    public $name;

    /**
     * @Assert\Type("string")
     */
    public $description;

    /**
     * @Assert\Type("string")
     * @Assert\NotNull()
     */
    public $picto;

    /**
     * @Assert\Type("float")
     * @Assert\NotNull()
     */
    public $point;

    /**
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    public $isRecurrent;

    /**
     * @Assert\Type("bool")
     * @Assert\NotNull
     */
    public $isPublic;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    public $userId;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    public $homeId;
}