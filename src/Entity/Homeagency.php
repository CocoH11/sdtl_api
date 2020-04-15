<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\HomeagencyRepository")
 */
class Homeagency
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom fourni n'est pas valide")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Truck", mappedBy="homeagency")
     */
    private $trucks;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Integrationmodel", inversedBy="homeagencies")
     */
    private $integrationmodels;


    public function __construct()
    {
        $this->trucks = new ArrayCollection();
        $this->integrationmodels = new ArrayCollection();
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
     * @return Collection|Truck[]
     */
    public function getTrucks(): Collection
    {
        return $this->trucks;
    }

    public function addTruck(Truck $truck): self
    {
        if (!$this->trucks->contains($truck)) {
            $this->trucks[] = $truck;
            $truck->setHomeagency($this);
        }

        return $this;
    }

    public function removeTruck(Truck $truck): self
    {
        if ($this->trucks->contains($truck)) {
            $this->trucks->removeElement($truck);
            // set the owning side to null (unless already changed)
            if ($truck->getHomeagency() === $this) {
                $truck->setHomeagency(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Integrationmodel[]
     */
    public function getIntegrationmodels(): Collection
    {
        return $this->integrationmodels;
    }

    public function addIntegrationmodel(Integrationmodel $integrationmodel): self
    {
        if (!$this->integrationmodels->contains($integrationmodel)) {
            $this->integrationmodels[] = $integrationmodel;
        }

        return $this;
    }

    public function removeIntegrationmodel(Integrationmodel $integrationmodel): self
    {
        if ($this->integrationmodels->contains($integrationmodel)) {
            $this->integrationmodels->removeElement($integrationmodel);
        }

        return $this;
    }
}
