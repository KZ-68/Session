<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SessionRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Assert permet d'ajouter des contraintes fixes sur les attributs de l'entité
    // Elle sera appelée lors de l'ajout ou d'une modification dans un formulaire
    // Si vous avez besoin d'un formulaire spécifique, ajoutez des contraintes personnalisées dans le FormType
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThanOrEqual(
        value : 'today',
        message: 'Vous devez choisir une date commençant aujourdhui ou supérieur à la date du jour'
    )]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan(
        value: 'today',
        message: 'Vous devez choisir une date supérieur à la date du jour'
    )]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column]
    private ?int $nbMax = null;

    #[ORM\ManyToMany(targetEntity: Stagiaire::class, inversedBy: 'sessions')]
    private Collection $stagiaires;

    #[ORM\ManyToOne(inversedBy: 'sessions')]
    #[ORM\JoinColumn(nullable: true,  onDelete:"SET NULL")]
    private ?Formation $formation = null;

    #[ORM\OneToMany(mappedBy: 'session', targetEntity: Programme::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $programmes;

    public function __construct()
    {
        $this->stagiaires = new ArrayCollection();
        $this->programmes = new ArrayCollection();
    }

    public function __toString() {
        return $this->formation->getIntituleFormation(). " ".$this->dateDebut->format('m Y');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getNbMax(): ?int
    {
        return $this->nbMax;
    }

    public function setNbMax(int $nbMax): static
    {
        $this->nbMax = $nbMax;

        return $this;
    }

    /**
     * @return Collection<int, Stagiaire>
     */
    public function getStagiaires(): Collection
    {
        return $this->stagiaires;
    }

    public function addStagiaire(Stagiaire $stagiaire): static
    {
        if (!$this->stagiaires->contains($stagiaire)) {
            $this->stagiaires->add($stagiaire);
        }

        return $this;
    }

    public function removeStagiaire(Stagiaire $stagiaire): static
    {
        $this->stagiaires->removeElement($stagiaire);

        return $this;
    }

    public function getFormation(): ?Formation
    {
        return $this->formation;
    }

    public function setFormation(?Formation $formation): static
    {
        $this->formation = $formation;

        return $this;
    }

    /**
     * @return Collection<int, Programme>
     */
    public function getProgrammes(): Collection
    {
        return $this->programmes;
    }

    public function addProgramme(Programme $programme): static
    {
        if (!$this->programmes->contains($programme)) {
            $this->programmes->add($programme);
            $programme->setSession($this);
        }

        return $this;
    }

    public function removeProgramme(Programme $programme): static
    {
        if ($this->programmes->removeElement($programme)) {
            // set the owning side to null (unless already changed)
            if ($programme->getSession() === $this) {
                $programme->setSession(null);
            }
        }

        return $this;
    }

    public function getNbInscrit(): ?int {
        return count($this->stagiaires);
    }

    public function getNbPlacesRestante(): ?int {
        $restant = $this->nbMax - count($this->stagiaires);
        return $restant;
    }

    public function getDureeDate(): ?string {
        return "(du ".$this->dateDebut->format('d/m/Y')." au ".$this->dateFin->format('d/m/Y').")";
    }

    public function getCurrentTime(): ?\DateTime{
        $currentTime = new DateTime();
        $currentTime->format('d/m/Y');
        return $currentTime;
    }
} 
