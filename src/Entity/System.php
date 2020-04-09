<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SystemRepository")
 */
class System
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Integrationmodel", mappedBy="system")
     */
    private $integrationmodels;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Code", mappedBy="system")
     */
    private $codes;

    public function __construct()
    {
        $this->integrationmodels = new ArrayCollection();
        $this->codes = new ArrayCollection();
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
            $integrationmodel->setSystem($this);
        }

        return $this;
    }

    public function removeIntegrationmodel(Integrationmodel $integrationmodel): self
    {
        if ($this->integrationmodels->contains($integrationmodel)) {
            $this->integrationmodels->removeElement($integrationmodel);
            // set the owning side to null (unless already changed)
            if ($integrationmodel->getSystem() === $this) {
                $integrationmodel->setSystem(null);
            }
        }

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
            $code->setSystem($this);
        }

        return $this;
    }

    public function removeCode(Code $code): self
    {
        if ($this->codes->contains($code)) {
            $this->codes->removeElement($code);
            // set the owning side to null (unless already changed)
            if ($code->getSystem() === $this) {
                $code->setSystem(null);
            }
        }

        return $this;
    }
}
