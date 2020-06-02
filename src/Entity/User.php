<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $login;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Homeagency", inversedBy="users")
     */
    private $homeagency;

    /**
    * @ORM\Column(type="string", unique=true, nullable=true, name="api_token")
    */
    private $apiToken;

    /**
     * @ORM\OneToMany(targetEntity=Refuel::class, mappedBy="creatorUser")
     */
    private $createdRefuels;

    /**
     * @ORM\OneToMany(targetEntity=Refuel::class, mappedBy="modifierUser")
     */
    private $modifiedRefuels;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $email;

    public function __construct()
    {
        $this->createdRefuels = new ArrayCollection();
        $this->modifiedRefuels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    /**
     * @see UserInterface
     */
    public function getApiToken(): ?string
    {
        return $this->apiToken;
    }

    public function setApiToken(?string $apiToken): self
    {
        $this->apiToken = $apiToken;

        return $this;
    }

    /**
     * @return Collection|Refuel[]
     */
    public function getCreatedRefuels(): Collection
    {
        return $this->createdRefuels;
    }

    public function addRefuel(Refuel $refuel): self
    {
        if (!$this->createdRefuels->contains($refuel)) {
            $this->createdRefuels[] = $refuel;
            $refuel->setCreatorUser($this);
        }

        return $this;
    }

    public function removeRefuel(Refuel $refuel): self
    {
        if ($this->createdRefuels->contains($refuel)) {
            $this->createdRefuels->removeElement($refuel);
            // set the owning side to null (unless already changed)
            if ($refuel->getCreatorUser() === $this) {
                $refuel->setCreatorUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Refuel[]
     */
    public function getModifiedRefuels(): Collection
    {
        return $this->modifiedRefuels;
    }

    public function addModifiedRefuel(Refuel $modifiedRefuel): self
    {
        if (!$this->modifiedRefuels->contains($modifiedRefuel)) {
            $this->modifiedRefuels[] = $modifiedRefuel;
            $modifiedRefuel->setModifierUser($this);
        }

        return $this;
    }

    public function removeModifiedRefuel(Refuel $modifiedRefuel): self
    {
        if ($this->modifiedRefuels->contains($modifiedRefuel)) {
            $this->modifiedRefuels->removeElement($modifiedRefuel);
            // set the owning side to null (unless already changed)
            if ($modifiedRefuel->getModifierUser() === $this) {
                $modifiedRefuel->setModifierUser(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
