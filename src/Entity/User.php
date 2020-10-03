<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $points = 0;

    /**
     * @ORM\OneToMany(targetEntity=Task::class, mappedBy="addedBy")
     */
    private $addedTasks;

    /**
     * @ORM\ManyToMany(targetEntity=Home::class, inversedBy="users")
     */
    private $home;

    public function __construct()
    {
        $this->addedTasks = new ArrayCollection();
        $this->home = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPoints(): ?float
    {
        return $this->points;
    }

    public function setPoints(float $points): self
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getAddedTasks(): Collection
    {
        return $this->addedTasks;
    }

    public function addAddedTask(Task $addedTask): self
    {
        if (!$this->addedTasks->contains($addedTask)) {
            $this->addedTasks[] = $addedTask;
            $addedTask->setAddedBy($this);
        }

        return $this;
    }

    public function removeAddedTask(Task $addedTask): self
    {
        if ($this->addedTasks->contains($addedTask)) {
            $this->addedTasks->removeElement($addedTask);
            // set the owning side to null (unless already changed)
            if ($addedTask->getAddedBy() === $this) {
                $addedTask->setAddedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Home[]
     */
    public function getHome(): Collection
    {
        return $this->home;
    }

    public function addHome(Home $home): self
    {
        if (!$this->home->contains($home)) {
            $this->home[] = $home;
        }

        return $this;
    }

    public function removeHome(Home $home): self
    {
        if ($this->home->contains($home)) {
            $this->home->removeElement($home);
        }

        return $this;
    }
}
