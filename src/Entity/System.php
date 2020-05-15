<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SystemRepository")
 */
class System
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Refuel", mappedBy="system")
     */
    private $refuels;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $directoryName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Homeagency", mappedBy="systems")
     */
    private $homeagencies;

    public function __construct()
    {
        $this->refuels = new ArrayCollection();
        $this->homeagencies = new ArrayCollection();
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
            $refuel->setSystem($this);
        }

        return $this;
    }

    public function removeRefuel(Refuel $refuel): self
    {
        if ($this->refuels->contains($refuel)) {
            $this->refuels->removeElement($refuel);
            // set the owning side to null (unless already changed)
            if ($refuel->getSystem() === $this) {
                $refuel->setSystem(null);
            }
        }

        return $this;
    }

    public function getDirectoryName(): ?string
    {
        return $this->directoryName;
    }

    public function setDirectoryName(string $directoryName): self
    {
        $this->directoryName = $directoryName;

        return $this;
    }

    /**
     * @return Collection|Homeagency[]
     */
    public function getHomeagencies(): Collection
    {
        return $this->homeagencies;
    }

    public function addHomeagency(Homeagency $homeagency): self
    {
        if (!$this->homeagencies->contains($homeagency)) {
            $this->homeagencies[] = $homeagency;
            $homeagency->addSystem($this);
        }

        return $this;
    }

    public function removeHomeagency(Homeagency $homeagency): self
    {
        if ($this->homeagencies->contains($homeagency)) {
            $this->homeagencies->removeElement($homeagency);
            $homeagency->removeSystem($this);
        }

        return $this;
    }
}
