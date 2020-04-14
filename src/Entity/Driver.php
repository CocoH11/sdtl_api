<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DriverRepository")
 */
class Driver
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $firstname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Refuel", mappedBy="driver")
     */
    private $refuels;

    public function __construct()
    {
        $this->refuels = new ArrayCollection();
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * @return Collection|Refuel[]
     */
    public function getRefuels(): Collection
    {
        return $this->refuels;
    }

    public function addRefuel(Refuel $refuel): self
    {
        if (!$this->refuels->contains($refuel)) {
            $this->refuels[] = $refuel;
            $refuel->setDriver($this);
        }

        return $this;
    }

    public function removeRefuel(Refuel $refuel): self
    {
        if ($this->refuels->contains($refuel)) {
            $this->refuels->removeElement($refuel);
            // set the owning side to null (unless already changed)
            if ($refuel->getDriver() === $this) {
                $refuel->setDriver(null);
            }
        }

        return $this;
    }
}
