<?php

namespace App\Entity;

use App\Repository\StagiaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: StagiaireRepository::class)]
class Stagiaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(
        message: 'Le nom {{ value }} ne peut pas être laissée vide'
    )]
    #[Assert\NoSuspiciousCharacters(
        message: 'Le nom {{ value }} contient des caractères non valide'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(
        message: 'Le prénom {{ value }} ne peut pas être laissée vide'
    )]
    #[Assert\NoSuspiciousCharacters(
        message: 'Le prénom {{ value }} contient des caractères non valide'
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 10)]
    #[Assert\NoSuspiciousCharacters(
        message: 'Le genre {{ value }} contient des caractères non valide'
    )]
    private ?string $genre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'La ville {{ value }} ne peut pas être laissée vide'
    )]
    #[Assert\NoSuspiciousCharacters(
        message: 'La ville {{ value }} contient des caractères non valide'
    )]
    private ?string $ville = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: 'L\'email {{ value }} ne peut pas être laissée vide'
    )]
    #[Assert\Email(
        message: 'L\'email est invalide'
    )]
    private ?string $email = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\NoSuspiciousCharacters(
        message: 'Le numéro de {{ value }} contient des caractères non valide'
    )]
    private ?string $telephone = null;

    #[ORM\ManyToMany(targetEntity: Session::class, mappedBy: 'stagiaires')]
    private Collection $sessions;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    public function __toString() {
        return $this->prenom." ".$this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->addStagiaire($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            $session->removeStagiaire($this);
        }

        return $this;
    }
}
