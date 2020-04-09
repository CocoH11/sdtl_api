<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IntegrationmodelRepository")
 */
class Integrationmodel
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
    private $volumelocation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $datelocation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $dateformatlocation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codetrucklocation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codedriverlocation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mileagetrucklocation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Homeagency", mappedBy="integrationmodels")
     */
    private $homeagencies;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\System", inversedBy="integrationmodels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $system;

    public function __construct()
    {
        $this->homeagencies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVolumelocation(): ?string
    {
        return $this->volumelocation;
    }

    public function setVolumelocation(string $volumelocation): self
    {
        $this->volumelocation = $volumelocation;

        return $this;
    }

    public function getDatelocation(): ?string
    {
        return $this->datelocation;
    }

    public function setDatelocation(string $datelocation): self
    {
        $this->datelocation = $datelocation;

        return $this;
    }

    public function getDateformatlocation(): ?string
    {
        return $this->dateformatlocation;
    }

    public function setDateformatlocation(string $dateformatlocation): self
    {
        $this->dateformatlocation = $dateformatlocation;

        return $this;
    }

    public function getCodetrucklocation(): ?string
    {
        return $this->codetrucklocation;
    }

    public function setCodetrucklocation(string $codetrucklocation): self
    {
        $this->codetrucklocation = $codetrucklocation;

        return $this;
    }

    public function getCodedriverlocation(): ?string
    {
        return $this->codedriverlocation;
    }

    public function setCodedriverlocation(?string $codedriverlocation): self
    {
        $this->codedriverlocation = $codedriverlocation;

        return $this;
    }

    public function getMileagetrucklocation(): ?string
    {
        return $this->mileagetrucklocation;
    }

    public function setMileagetrucklocation(string $mileagetrucklocation): self
    {
        $this->mileagetrucklocation = $mileagetrucklocation;

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
            $homeagency->addIntegrationmodel($this);
        }

        return $this;
    }

    public function removeHomeagency(Homeagency $homeagency): self
    {
        if ($this->homeagencies->contains($homeagency)) {
            $this->homeagencies->removeElement($homeagency);
            $homeagency->removeIntegrationmodel($this);
        }

        return $this;
    }

    public function getSystem(): ?System
    {
        return $this->system;
    }

    public function setSystem(?System $system): self
    {
        $this->system = $system;

        return $this;
    }
}
