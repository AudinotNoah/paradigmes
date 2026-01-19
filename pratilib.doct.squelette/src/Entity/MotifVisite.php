<?php

namespace toubeelib\praticien\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'motif_visite')]
class MotifVisite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 128)]
    private string $libelle;

    #[ORM\ManyToOne(targetEntity: Specialite::class, inversedBy: 'motifsVisite')]
    #[ORM\JoinColumn(name: 'specialite_id', referencedColumnName: 'id', nullable: false)]
    private Specialite $specialite;

    #[ORM\ManyToMany(targetEntity: Praticien::class, mappedBy: 'motifsVisite')]
    private Collection $praticiens;

    public function __construct()
    {
        $this->praticiens = new ArrayCollection();
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

    public function getSpecialite(): Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(Specialite $specialite): self
    {
        $this->specialite = $specialite;
        return $this;
    }

    public function getPraticiens(): Collection
    {
        return $this->praticiens;
    }
}
