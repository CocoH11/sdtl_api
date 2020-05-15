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
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="homeagency")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Refuel", mappedBy="homeagency")
     */
    private $refuels;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $directoryname;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\System", inversedBy="homeagencies")
     */
    private $systems;


    public function __construct()
    {
        $this->trucks = new ArrayCollection();
        $this->integrationmodels = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->drivers = new ArrayCollection();
        $this->refuels = new ArrayCollection();
        $this->systems = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setHomeagency($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getHomeagency() === $this) {
                $user->setHomeagency(null);
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
            $refuel->setHomeagency($this);
        }

        return $this;
    }

    public function removeRefuel(Refuel $refuel): self
    {
        if ($this->refuels->contains($refuel)) {
            $this->refuels->removeElement($refuel);
            // set the owning side to null (unless already changed)
            if ($refuel->getHomeagency() === $this) {
                $refuel->setHomeagency(null);
            }
        }

        return $this;
    }

    public function getDirectoryname(): ?string
    {
        return $this->directoryname;
    }

    public function setDirectoryname(string $directoryname): self
    {
        $this->directoryname = $directoryname;

        return $this;
    }

    /**
     * @return Collection|System[]
     */
    public function getSystems(): Collection
    {
        return $this->systems;
    }

    public function addSystem(System $system): self
    {
        if (!$this->systems->contains($system)) {
            $this->systems[] = $system;
        }

        return $this;
    }

    public function removeSystem(System $system): self
    {
        if ($this->systems->contains($system)) {
            $this->systems->removeElement($system);
        }

        return $this;
    }

}
