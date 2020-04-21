<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


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
     * @Assert\NotBlank(message="L'emplacement du volume de carburant fourni n'est pas valide")
     */
    private $volumelocation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'emplacement de la date fourni n'est pas valide")
     */
    private $datelocation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le format de date fourni n'est pas valide")
     */
    private $dateformat;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'emplacement du code du véhicule n'est pas valide")
     */
    private $codetrucklocation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="L'emplacement du code du chauffeur n'est pas valide")
     */
    private $codedriverlocation;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="L'emplacement du kilométrage du véhicule n'est pas valide")
     */
    private $mileagetrucklocation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\System", inversedBy="integrationmodels")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Le système fourni est invalide")
     */
    private $system;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Homeagency", inversedBy="integrationmodels")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="L'agence de rattachement fournie n'est pas valide")
     */
    private $homeagency;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

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

    public function getSystem(): ?System
    {
        return $this->system;
    }

    public function setSystem(?System $system): self
    {
        $this->system = $system;

        return $this;
    }

    public function getDateformat(): ?string
    {
        return $this->dateformat;
    }

    public function setDateformat(string $dateformat): self
    {
        $this->dateformat = $dateformat;

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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
