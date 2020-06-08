<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefuelRepository")
 * @UniqueEntity(fields={"codeCard", "codeDriver","date"}, message="This line already exists", payload={"error_code"=REFUEL::ERROR_DUPLICATE})
 */
class Refuel
{
    const ERROR_DUPLICATE="duplicate";
    const ERROR_VOLUME="volume";
    const ERROR_CODECARD="codecard";
    const ERROR_CODEDRIVER="codedriver";
    const ERROR_SYSTEM="system";
    const ERROR_HOMEAGENCY="homeagency";
    const ERROR_DATE="date";
    const ERROR_STATIONLOCATION="stationlocation";
    const ERROR_MILEAGE="mileage";
    const ERROR_PRODUCT="product";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotNull(message="No volume available", payload={"error_code"=REFUEL::ERROR_VOLUME})
     * @Assert\Positive(message="The volume specified is abnormal", payload={"error_code"=REFUEL::ERROR_VOLUME})
     * @Assert\LessThan(value="1000", message="The volume specified is abnormal", payload={"error_code"=REFUEL::ERROR_VOLUME})
     */
    private ?float $volume;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="No card code available", payload={"error_code"=REFUEL::ERROR_CODECARD})
     * @Assert\NotBlank(message="No card code available", payload={"error_code"=REFUEL::ERROR_CODECARD})
     */
    private ?string $codeCard;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotNull(message="No Driver code available", payload={"error_code"=REFUEL::ERROR_CODEDRIVER})
     * @Assert\NotBlank(message="No Driver code available", payload={"error_code"=REFUEL::ERROR_CODEDRIVER})
     */
    private ?string $codeDriver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\System", inversedBy="refuels")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?System $system;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Homeagency", inversedBy="refuels")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Homeagency $homeagency;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull(message="No date available", payload={"error_code"=REFUEL::ERROR_DATE})
     * @Assert\NotBlank(message="no date available", payload={"error_code"=REFUEL::ERROR_DATE})
     * @Assert\Type(type="datetime", message="the date is not valide", payload={"error_code"=REFUEL::ERROR_DATE})
     * @Assert\GreaterThan("-20 years", payload={"error_code"=REFUEL::ERROR_DATE})
     */
    private ?\DateTimeInterface $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull(message="No station location available", payload={"error_code"=REFUEL::ERROR_STATIONLOCATION})
     * @Assert\NotBlank(message="No station location available", payload={"error_code"=REFUEL::ERROR_STATIONLOCATION})
     */
    private ?string $stationLocation;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull(message="No mileage available")
     * @Assert\Positive(message="The mileage specified is abnormal", payload={"error_code"=REFUEL::ERROR_MILEAGE})
     * @Assert\LessThan(value="500000000", message="The mileage specified is abnormal", payload={"error_code"=REFUEL::ERROR_MILEAGE})
     */
    private ?int $mileage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="refuels")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Product $product;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private ?\DateTimeInterface $creationDate;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="refuels")
     */
    private ?User $creatorUser;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $modificationDate;

    /**
     * @ORM\JoinColumn(nullable=true)
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="modifiedRefuels")
     */
    private ?User $modifierUser;

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

    public function getStationLocation(): ?string
    {
        return $this->stationLocation;
    }

    public function setStationLocation(string $stationLocation): self
    {
        $this->stationLocation = $stationLocation;

        return $this;
    }

    public function getMileage(): ?int
    {
        return $this->mileage;
    }

    public function setMileage(int $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getCreatorUser(): ?User
    {
        return $this->creatorUser;
    }

    public function setCreatorUser(?User $creatorUser): self
    {
        $this->creatorUser = $creatorUser;

        return $this;
    }

    public function getModificationDate(): ?\DateTimeInterface
    {
        return $this->modificationDate;
    }

    public function setModificationDate(\DateTimeInterface $modificationDate): self
    {
        $this->modificationDate = $modificationDate;

        return $this;
    }

    public function getModifierUser(): ?User
    {
        return $this->modifierUser;
    }

    public function setModifierUser(?User $modifierUser): self
    {
        $this->modifierUser = $modifierUser;

        return $this;
    }
}
