<?php


namespace App\Domain\Model;

use Symfony\Component\Validator\Constraints as Assert;

class DoneTask
{
    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    public $taskId;

    /**
     * @Assert\NotBlank
     * @Assert\NotNull
     */
    public $userId;
}