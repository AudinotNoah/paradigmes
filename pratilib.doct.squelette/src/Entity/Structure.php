<?php

namespace toubeelib\praticien\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

#[ORM\Entity]
#[ORM\Table(name: 'structure')]
class Structure
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string', length: 48)]
    private string $nom;

    #[ORM\Column(type: 'text')]
    private string $adresse;

    #[ORM\Column(type: 'string', length: 128, nullable: true)]
    private ?string $ville = null;

    #[ORM\Column(name: 'code_postal', type: 'string', length: 12, nullable: true)]
    private ?string $codePostal = null;

    #[ORM\Column(type: 'string', length: 24, nullable: true)]
    private ?string $telephone = null;

    #[ORM\OneToMany(mappedBy: 'structure', targetEntity: Praticien::class)]
    private Collection $praticiens;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->praticiens = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getAdresse(): string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getPraticiens(): Collection
    {
        return $this->praticiens;
    }
}
