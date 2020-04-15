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
     * @ORM\Column(type="integer")
     * @Assert\LessThanOrEqual(
     *     value=200,
     *     message="le volume de carburant n'est pas valide"
     * )
     */
    private $volume;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Driver", inversedBy="refuels")
     */
    //@Assert\NotNull(message="le chauffeur entré est invalide")

    private $driver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Truck", inversedBy="refuels")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="le véhicule entré est invalide")
     */
    private $truck;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVolume(): ?int
    {
        return $this->volume;
    }

    public function setVolume(int $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getDriver(): ?Driver
    {
        return $this->driver;
    }

    public function setDriver(?Driver $driver): self
    {
        $this->driver = $driver;

        return $this;
    }

    public function getTruck(): ?Truck
    {
        return $this->truck;
    }

    public function setTruck(?Truck $truck): self
    {
        $this->truck = $truck;

        return $this;
    }
}
