<?php

namespace App\Entity;

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
     * @ORM\OneToOne(targetEntity=RefreshToken::class, mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="refreshToken", nullable=true)
     */
    private $refreshToken;

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
     * @see UserInterface
     */
    public function getRefreshToken(): ?RefreshToken
    {
        return $this->refreshToken;
    }

    public function setRefreshToken(?RefreshToken $refreshToken): self
    {
        $this->refreshToken = $refreshToken;

        // set (or unset) the owning side of the relation if necessary
        $newUser = null === $refreshToken ? null : $this;
        if ($refreshToken->getUser() !== $newUser) {
            $refreshToken->setUser($newUser);
        }

        return $this;
    }
}
