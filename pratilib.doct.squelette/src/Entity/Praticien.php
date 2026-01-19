<?php

namespace toubeelib\praticien\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

#[ORM\Entity(repositoryClass: \toubeelib\praticien\Repository\PraticienRepository::class)]
#[ORM\Table(name: 'praticien')]
class Praticien
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36)]
    private string $id;

    #[ORM\Column(type: 'string', length: 48)]
    private string $nom;

    #[ORM\Column(type: 'string', length: 48)]
    private string $prenom;

    #[ORM\Column(type: 'string', length: 48)]
    private string $ville;

    #[ORM\Column(type: 'string', length: 128)]
    private string $email;

    #[ORM\Column(type: 'string', length: 24)]
    private string $telephone;

    #[ORM\Column(name: 'rpps_id', type: 'string', length: 12, nullable: true)]
    private ?string $rppsId = null;

    #[ORM\Column(type: 'string', length: 1, options: ['default' => '0'])]
    private string $organisation = '0';

    #[ORM\Column(name: 'nouveau_patient', type: 'string', length: 1, options: ['default' => '1'])]
    private string $nouveauPatient = '1';

    #[ORM\Column(type: 'string', length: 8, options: ['default' => 'Dr.'])]
    private string $titre = 'Dr.';

    #[ORM\ManyToOne(targetEntity: Specialite::class, inversedBy: 'praticiens')]
    #[ORM\JoinColumn(name: 'specialite_id', referencedColumnName: 'id', nullable: false)]
    private Specialite $specialite;

    #[ORM\ManyToOne(targetEntity: Structure::class, inversedBy: 'praticiens')]
    #[ORM\JoinColumn(name: 'structure_id', referencedColumnName: 'id', nullable: true)]
    private ?Structure $structure = null;

    #[ORM\ManyToMany(targetEntity: MotifVisite::class, inversedBy: 'praticiens')]
    #[ORM\JoinTable(name: 'praticien2motif')]
    #[ORM\JoinColumn(name: 'praticien_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'motif_id', referencedColumnName: 'id')]
    private Collection $motifsVisite;

    #[ORM\ManyToMany(targetEntity: MoyenPaiement::class, inversedBy: 'praticiens')]
    #[ORM\JoinTable(name: 'praticien2moyen')]
    #[ORM\JoinColumn(name: 'praticien_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'moyen_id', referencedColumnName: 'id')]
    private Collection $moyensPaiement;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->motifsVisite = new ArrayCollection();
        $this->moyensPaiement = new ArrayCollection();
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

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getRppsId(): ?string
    {
        return $this->rppsId;
    }

    public function setRppsId(?string $rppsId): self
    {
        $this->rppsId = $rppsId;
        return $this;
    }

    public function isOrganisation(): bool
    {
        return $this->organisation === '1';
    }

    public function setOrganisation(bool $organisation): self
    {
        $this->organisation = $organisation ? '1' : '0';
        return $this;
    }

    public function isNouveauPatient(): bool
    {
        return $this->nouveauPatient === '1';
    }

    public function setNouveauPatient(bool $nouveauPatient): self
    {
        $this->nouveauPatient = $nouveauPatient ? '1' : '0';
        return $this;
    }

    public function getTitre(): string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
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

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): self
    {
        $this->structure = $structure;
        return $this;
    }

    public function getMotifsVisite(): Collection
    {
        return $this->motifsVisite;
    }

    public function addMotifVisite(MotifVisite $motif): self
    {
        if (!$this->motifsVisite->contains($motif)) {
            $this->motifsVisite->add($motif);
        }
        return $this;
    }

    public function removeMotifVisite(MotifVisite $motif): self
    {
        $this->motifsVisite->removeElement($motif);
        return $this;
    }

    public function getMoyensPaiement(): Collection
    {
        return $this->moyensPaiement;
    }

    public function addMoyenPaiement(MoyenPaiement $moyen): self
    {
        if (!$this->moyensPaiement->contains($moyen)) {
            $this->moyensPaiement->add($moyen);
        }
        return $this;
    }

    public function removeMoyenPaiement(MoyenPaiement $moyen): self
    {
        $this->moyensPaiement->removeElement($moyen);
        return $this;
    }
}
