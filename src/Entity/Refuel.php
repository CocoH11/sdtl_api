<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\RefuelRepository")
 */
class Refuel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $volume;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $codeCard;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $codeDriver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\System", inversedBy="refuels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $system;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Homeagency", inversedBy="refuels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $homeagency;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodeCard(): ?string
    {
        return $this->codeCard;
    }

    public function setCodeCard(string $codeCard): self
    {
        $this->codeCard = $codeCard;

        return $this;
    }

    public function getCodeDriver(): ?string
    {
        return $this->codeDriver;
    }

    public function setCodeDriver(?string $codeDriver): self
    {
        $this->codeDriver = $codeDriver;

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

    public function getHomeagency(): ?Homeagency
    {
        return $this->homeagency;
    }

    public function setHomeagency(?Homeagency $homeagency): self
    {
        $this->homeagency = $homeagency;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(float $volume): self
    {
        $this->volume = $volume;

        return $this;
    }
}
