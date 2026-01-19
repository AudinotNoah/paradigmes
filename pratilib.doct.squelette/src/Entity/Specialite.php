<?php

namespace toubeelib\praticien\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: \toubeelib\praticien\Repository\SpecialiteRepository::class)]
#[ORM\Table(name: 'specialite')]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 48)]
    private string $libelle;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: Praticien::class)]
    private Collection $praticiens;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: MotifVisite::class)]
    private Collection $motifsVisite;

    public function __construct()
    {
        $this->praticiens = new ArrayCollection();
        $this->motifsVisite = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getPraticiens(): Collection
    {
        return $this->praticiens;
    }

    public function getMotifsVisite(): Collection
    {
        return $this->motifsVisite;
    }
}
