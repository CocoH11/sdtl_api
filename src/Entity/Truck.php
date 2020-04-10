<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TruckRepository")
 */
class Truck
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
    private $numberplate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Type", inversedBy="trucks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Homeagency", inversedBy="trucks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $homeagency;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Activity", inversedBy="trucks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $activity;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Code", mappedBy="truck")
     */
    private $codes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Refuel", mappedBy="truck")
     */
    private $refuels;

    public function __construct()
    {
        $this->codes = new ArrayCollection();
        $this->refuels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberplate(): ?string
    {
        return $this->numberplate;
    }

    public function setNumberplate(string $numberplate): self
    {
        $this->numberplate = $numberplate;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getHomeagency(): ?Homeagency
    {
        return $this->homeagency;
    }

    public function setHomeagency(?Homeagency $homeagency): self
    {
        $this->homeagency = $homeagency;

        return $this;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): self
    {
        $this->activity = $activity;

        return $this;
    }

    /**
     * @return Collection|Code[]
     */
    public function getCodes(): Collection
    {
        return $this->codes;
    }

    public function addCode(Code $code): self
    {
        if (!$this->codes->contains($code)) {
            $this->codes[] = $code;
            $code->setTruck($this);
        }

        return $this;
    }

    public function removeCode(Code $code): self
    {
        if ($this->codes->contains($code)) {
            $this->codes->removeElement($code);
            // set the owning side to null (unless already changed)
            if ($code->getTruck() === $this) {
                $code->setTruck(null);
            }
        }

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
            $refuel->setTruck($this);
        }

        return $this;
    }

    public function removeRefuel(Refuel $refuel): self
    {
        if ($this->refuels->contains($refuel)) {
            $this->refuels->removeElement($refuel);
            // set the owning side to null (unless already changed)
            if ($refuel->getTruck() === $this) {
                $refuel->setTruck(null);
            }
        }

        return $this;
    }
}
